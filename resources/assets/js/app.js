var app = angular.module('app',['ngRoute','angular-oauth2','app.controllers', 'app.services']);

angular.module('app.controllers',['ngMessages','angular-oauth2']);
angular.module('app.services',['ngResource']);

app.provider('appConfig', ['$httpParamSerializerProvider',
    function($httpParamSerializerProvider){
    var config;
    config = {
        baseUrl: 'http://localhost:8000',

        utils: {
            transformRequest: function (data) {
                if (angular.isObject(data)) {
                    return $httpParamSerializerProvider.$get()(data);
                }
                return data;
            },
            transformResponse: function (data, headers) {
                var headersGetter = headers();
                //console.log(data);
                //console.log(headers);

                if (headersGetter['content-type'] == 'application/json' ||
                    headersGetter['content-type'] == 'text/json') {

                    var dataJson = JSON.parse(data);
                    // se tiver a propriedade 'data' e somente uma propriedade dentro do objeto
                    if (dataJson.hasOwnProperty('data') && Object.keys(dataJson).length == 1) {
                        dataJson = dataJson.data;
                    }
                    return dataJson;
                }

                return data;
            }
        }
    };

    return {
        config: config,
        $get: function(){
            return config;
        }
    }
}]);

app.config([
    '$routeProvider', '$httpProvider','OAuthProvider','OAuthTokenProvider','appConfigProvider',
     function($routeProvider, $httpProvider, OAuthProvider, OAuthTokenProvider, appConfigProvider){

         $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
         $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

         $httpProvider.defaults.transformRequest = appConfigProvider.config.utils.transformRequest;
   $routeProvider
       .when('/login', {
           templateUrl: 'build/views/login.html',
           controller: 'LoginController'
       })
       .when('/home', {
           templateUrl: 'build/views/home.html',
           controller: 'HomeController'
       })
       .when('/clients', {
           templateUrl: 'build/views/client/list.html',
           controller: 'ClientListController'
       })
       .when('/clients/new', {
           templateUrl: 'build/views/client/new.html',
           controller: 'ClientNewController'
       })
       .when('/clients/:id/edit', {
           templateUrl: 'build/views/client/edit.html',
           controller: 'ClientEditController'
       })
       .when('/clients/:id/remove', {
           templateUrl: 'build/views/client/remove.html',
           controller: 'ClientRemoveController'
       });

        OAuthProvider.configure({
            baseUrl: appConfigProvider.config.baseUrl,
            clientId: 'appid1',
            clientSecret: 'secret',
            grantPath: 'oauth/access_token'

        });

         OAuthTokenProvider.configure({
             name: 'token',
             options: {
                 secure: false
             }
         })
}]);

app.run(['$rootScope', '$window', 'OAuth', function($rootScope, $window, OAuth) {
    $rootScope.$on('oauth:error', function(event, rejection) {
        // Ignore `invalid_grant` error - should be catched on `LoginController`.
        if ('invalid_grant' === rejection.data.error) {
            return;
        }

        // Refresh token when a `invalid_token` error occurs.
        if ('invalid_token' === rejection.data.error) {
            return OAuth.getRefreshToken();
        }

        // Redirect to `/login` with the `error_reason`.
        return $window.location.href = '/login?error_reason=' + rejection.data.error;
    });
}]);