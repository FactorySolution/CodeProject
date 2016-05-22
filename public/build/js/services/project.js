angular.module('app.services')
    .service('Project', ['$resource', '$filter', '$httpParamSerializer', 'appConfig',
        function ($resource, $filter, $httpParamSerializer, appConfig) {

            function transformData(data) {
                if (angular.isObject(data) && data.hasOwnProperty('due_date')) {

                    data.due_date = $filter('date')(data.due_date, 'yyyy-MM-dd');
                    return appConfig.utils.transformRequest(data);
                }
                return data;
            };

            return $resource(appConfig.baseUrl + '/project/:id', {
                id: '@id'
            }, {
                update: {
                    method: 'PUT',
                    transformResponse: transformData
                },
                get: {
                    method: 'GET',
                    transformResponse: function(data, headers){
                        var o = appConfig.utils.transformResponse(data, headers);
                        if(angular.isObject(o) && o.hasOwnProperty('due_date')){

                            var arrayDate = o.due_date.split('-'),
                                month = parseInt(arrayDate[1])-1;

                            o.due_date = new Date(arrayDate[0],month,arrayDate[2]);
                        }
                        return o;
                    }
                },
                save: {
                    method: 'POST',
                    transformRequest: transformData
                }
            });
        }]);
