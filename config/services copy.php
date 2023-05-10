<?php

return [

    // Identity Provider Data that we want connect with our SP
    'idp' => [
        // Identifier of the IdP entity  (must be a URI)
        'entityId' => 'https://accounts.google.com/o/saml2?idpid=****',
        // SSO endpoint info of the IdP. (Authentication Request protocol)
        'singleSignOnService' => [
            // URL Target of the IdP where the SP will send the Authentication Request Message,
            // using HTTP-Redirect binding.
            'url' => 'https://accounts.google.com/o/saml2/idp?idpid=****',

        ],
        // SLO endpoint info of the IdP.
        'singleLogoutService' => [
            // URL Location of the IdP where the SP will send the SLO Request,
            // using HTTP-Redirect binding.
            'url' => 'https://accounts.google.com/Logout',
        ],
        // Public x509 certificate of the IdP
        'x509cert' => '****',
        /*
     *  Instead of use the whole x509cert you can use a fingerprint
     *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it)
     */
        // 'certFingerprint' => '',
        'routesMiddleware' => ['saml'],
    ]
];
