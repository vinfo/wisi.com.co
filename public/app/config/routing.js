var r = angular.module('Routing',['ngRoute']);

r.config(function($routeProvider){
    $routeProvider.
      when('/', {
        
        templateUrl: 'app/views/access.html',
        controller: 'AccessController'
      }).
      when('/home',{
        templateUrl: 'app/views/index.html',
        controller: 'IndexController'
      }).
      when('/terms', {
        templateUrl: 'app/views/terms.html',
        controller: 'TermsController'
      }).
      when('/help',{
        templateUrl: 'app/views/chat.html',
        controller: 'ChatController'
      }).
      
      when('/ad',{
        templateUrl: 'app/views/ad.html',
        controller: 'AdController'
      }).
      otherwise({
        redirectTo: '/'
      });
});


