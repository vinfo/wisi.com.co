'use strict';
(function(){
    var main = angular.module('Main',['Routing','task','angularFblogin']);
    //var resourceEndPoint='http://'+location.host+'/';
    var resourceEndPoint='http://wisi.com.co/';


/*index controller*/
main.controller('IndexController',function($scope){
    
});
/*terms controller*/
main.controller('TermsController',function($scope,$location){
    $scope.chkTerm=0;
    $scope.error='';
    localStorage.setItem("terms",0);
    
    $scope.terms=function(){
       localStorage.setItem("terms",$scope.chkTerm);
    }
   
    $scope.accept=function(){
       if($scope.chkTerm==1)
       {
        localStorage.setItem("terms", 1);   
        $location.path( "/" );
           
       }else{
           $scope.error='Debe aceptar los terminos y condiciones para continuar';
       }
    }
    
});
/*chat controller*/
main.controller('ChatController',function($scope){
    
})
/*access controller*/
main.controller('AccessController',function($scope,$http,$location,$fblogin,$window,Task){   
    $scope.islogin=false;
    $scope.isRegister=false;
    $scope.hasError=false;
    $scope.error='';
    $scope.isLoading=false;
    $scope.formData={
            name:'',
            lastname:'',
            address:'',
            company:'',
            nit:'',
            genre:1,
            email:'',
            password:'',
            email_register:'',
            password_register:'',
            birthday:'',
            phone:'',
            celphone:'',
            country:'',
            city:'',
            marital:'',
            newsletter:0,
            type_id:3
    }
    
    $scope.ShowLoginForm=function(){
        $scope.isRegister=false;
        $scope.islogin=true;
    }
    $scope.ShowRegisterForm=function(type){
        $scope.islogin=false;
        $scope.isRegister=true;
    }    

    /*actualizar vista de registro*/
    $scope.setUserType=function(){
            alert($scope.formData.type_id);
    }    
    /*obtener paises*/
    $http.get(resourceEndPoint+'api/countries/').success(function(res){
        $scope.countries=res;
    }).error(function(){});
    /*obtener ciudades*/
    $scope.getCities=function(){
            $http.get(resourceEndPoint+'api/cities/?code='+$scope.formData.country).success(function(res){
                 $scope.cities=res;

            }).error(function(){});
    }
    /*obtener marital*/
    $http.get(resourceEndPoint+'api/marital/').success(function(res){
        $scope.marital=res;
    }).error(function(){});
    
    
    $scope.FBlogin=function()
    {
      if(localStorage.getItem("terms")==0){
          $scope.hasError=true;
          alert('Debe aceptar los terminos y condiciones para continuar');
          return;
      }
      
      $fblogin({
                fbId:'190992121269957',
                permissions:'public_profile,email',
                fields:'id,first_name,last_name,gender,picture,email',
               
       }).then(function (data) {
                $http.post(resourceEndPoint+"api/social/sigin",
                           $.param({data:data}),
                            {headers : {'Content-Type': 'application/x-www-form-urlencoded'}}).success(function(res,status){

                                    localStorage.setItem("id", res.userdata.id);
                                    localStorage.setItem("name",  res.userdata.name);
                                    localStorage.setItem("lastname",  res.userdata.lastname);
                                    localStorage.setItem("email",  res.userdata.email);
                                    localStorage.setItem("img",  res.userdata.image);
                                    localStorage.setItem("logged_in", true);
                                    localStorage.setItem("token", res.token.token);
                     $location.path( "/ad" );

               }).error(function(error)
               {
                   $scope.hasError=true;
                   $scope.error='Error! Compruebe los datos de acceso y vuelva a intentarlo!';
               });
      });
    }
    
    $scope.login=function()
    {
      
      if($scope.formData.email!="")
      {

        if(!Task.validEmail($scope.formData.email))
        {
          $scope.hasError=true;
          $scope.error='Por favor ingrese un correo electronico valido!';
          return;
        }
      }
      $scope.hasError=false;
      $scope.error='';

      if ($scope.formData.email ==""||$scope.formData.password=="" )
      {
          $scope.hasError=true;
          $scope.error='Por favor ingrese un correo electronico! , Por favor ingrese una contraseña!';
          return;
      }
      if(localStorage.getItem("terms")==0){
          $scope.hasError=true;
          $scope.error='Debe aceptar los terminos y condiciones para continuar';
          return;
      }
      $scope.hasError=false;
      $scope.error='';
      $scope.isLoading=true;
      $http.post(resourceEndPoint+"api/sigin",
                           $.param({email:$scope.formData.email,password:$scope.formData.password}),
                            {headers : {'Content-Type': 'application/x-www-form-urlencoded'}}).success(function(res,status){
                           $scope.isLoading=false;
                           localStorage.setItem("id", res.userdata.id);
                           localStorage.setItem("name",  res.userdata.name);
                           localStorage.setItem("lastname",  res.userdata.lastname);
                           localStorage.setItem("email",  res.userdata.email);
                           localStorage.setItem("img",  res.userdata.image);
                           localStorage.setItem("logged_in", true);
                           localStorage.setItem("token", res.token.token);
            $location.path( "/ad" );

      }).error(function(error)
      {
          $scope.isLoading=false;
          $scope.hasError=true;
          $scope.error='Error! Compruebe los datos de acceso y vuelva a intentarlo!';
      });

    }/*end regular login*/
    
    /*register*/
    $scope.register =function(){
       var valid= Task.validateRegisterForm($scope.formData);
       if(valid!=''){
            $scope.hasError=true;
            $scope.error =valid;
            return false;
       }
       if(localStorage.getItem("terms")==0){
           $scope.hasError=true;
           $scope.error ="Debe aceptar los terminos y condiciones para continuar";
           return false;
       }
       $scope.isLoading=true;
       $http.post(resourceEndPoint+"api/register",
                           $.param({data:$scope.formData}),
                            {headers : {'Content-Type': 'application/x-www-form-urlencoded'}}).success(function(res,status){
                           $scope.isLoading=false;
                           localStorage.setItem("id", res.userdata.id);
                           localStorage.setItem("name",  res.userdata.name);
                           localStorage.setItem("lastname",  res.userdata.lastname);
                           localStorage.setItem("email",  res.userdata.email);
                           localStorage.setItem("img",  res.userdata.image);
                           localStorage.setItem("logged_in", true);
                           localStorage.setItem("token", res.token.token);
               if(res.redirect===true)
                    $window.location.href=resourceEndPoint+'campaign/my-campaigns';
               else
                    $location.path( "/ad" );   
            

      }).error(function(error,status)
      {
          $scope.isLoading=false;
          $scope.hasError=true;
          if(status==401){
              $scope.error='El correo elctronico ya esta asociado a una cuenta!';
              return false;
          }
          if(status==406){
              $scope.error='El usuario ya esta asociado a una cuenta!';
              return false;
          }
          $scope.error='Error! Compruebe los datos de acceso y vuelva a intentarlo!';
          
      });
      
    }
    
});
/*ad controller*/
main.controller('AdController',function($scope){
    
});


}());