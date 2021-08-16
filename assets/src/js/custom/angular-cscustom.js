angular.module('resourcesApp', [])
  .controller('ResourcesController', function( $scope, $http ) {
    var resourceList = this;
    resourceList.resource = [];
    var params = {
      "action":	"cs_wg_fetch_attachments_proxy",
      "orderby": "post_title",
      "order": "ASC",
      "page": 1,
      "ppp": 20,
      "lib": "",
      "media": "",
      "prod": "",
      "com": "",
      "search": ""
    };
    $scope.fetchResources = function() {
      $http({
        method: 'POST',
        url: ajax_object.ajax_url,
        dataType: 'json',
        params: params
      }).success(function(data, status, headers, config) {
        console.log( JSON.stringify( data, null, 4 ) );
        resourceList.resource = data;
      }).error(function(data, status, headers, config) {
        console.log( JSON.stringify( data, null, 4 ) );
      });
    };
    resourceList.clickme  = function() {
      console.log( "Done Been Clicked" );
    };
  });
