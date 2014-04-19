<?php

/**
 * Rapportive API class
 *
 * A small library to fetch user informations from rapportive API.
 * This is not an official library and shouldn't be used for any production level.
 * It's just for fun and the author doesn't take any lialibility/responsibility
 * of any kind whatsoever.
 *
 * @author Tareq Hasan <tareq@wedevs.com>
 * @link http://tareq.weDevs.com
 */
class WeDevs_Rapportive {

    private $email;

    function __construct( $email ) {
        $this->email = $email;
    }

    /**
     * Fetch the information and return the output
     *
     * @return array
     */
    public function get_data() {

        $session_token = $this->get_session();
        $info = $this->fetch_info( $session_token );

        return $this->format_data( $info );
    }

    /**
     * Get session key
     *
     * To send every request to rapportive API, there needs to be an session key.
     * It doesn't have to be unique for every request, but a valid session key
     * from any email. You can use the same session key for fetching many users
     * info. So caching the session key would be good idea.
     *
     * @return string
     */
    private function get_session() {
        $req = json_decode( file_get_contents( 'https://rapportive.com/login_status?user_email=' . $this->email ) );

        return $req->session_token;
    }

    /**
     * Fetch user information via cURL
     *
     * We are fetching the data by passing a session key as header and pretending to
     * send the request via Gmail interface.
     *
     * @param string $session_token
     * @return string
     */
    private function fetch_info( $session_token ) {
        $url = 'https://profiles.rapportive.com/contacts/email/' . $this->email;

        $curl = curl_init();

        curl_setopt_array( $curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.117 Safari/537.36',
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTPHEADER => array(
                'X-Session-Token: ' . $session_token
            )
        ) );

        $response = curl_exec( $curl );

        return $response;
    }

    /**
     * Format the information fetched
     *
     * @param string $info
     * @return array
     */
    private function format_data( $info ) {
        $info = json_decode( $info );

        $formatted = array(
            'name'     => isset( $info->contact->name ) ? $info->contact->name : '',
            'headline' => isset( $info->contact->headline ) ? $info->contact->headline : '',
            'avatar'   => isset( $info->contact->image_url_raw ) ? $info->contact->image_url_raw : '',
            'location' => isset( $info->contact->location ) ? $info->contact->location : '',
            'socials'  => array(),
        );

        if ( isset( $info->contact->memberships ) ) {
            foreach ($info->contact->memberships as $social) {
                $formatted['socials'][] = sprintf( '<a href="%s">%s</a>', $social->profile_url, $social->formatted );
            }
        }

        return $formatted;
    }

}