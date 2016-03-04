'use strict';
(function(){

var task =angular.module('task',[]);

task.factory('Task',function($http, $q){

	return {

		validEmail:function(input){
                    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    return re.test(input);
		},
                validateRegisterForm:function(obj){
                    var msj='';
                    if (obj.name=='') {
                        msj+='Nombres es requerido ';
                    } 
                    if (obj.lastname=='') {
                        msj+='Apellidos es requerido ';
                    } 
                    if (obj.email_register=='') {
                        msj+='Correo electronico es requerido ';
                    } 
                    if (!this.validEmail(obj.email_register)) {
                        msj+='Por favor ingrese un correo electronico valido! ';
                    } 
                    if (obj.password_register=='') {
                         msj+='Contraseña es requerida! ';
                    } 
                    if(obj.type_id==4){
                        
                        if(obj.username==''){
                            msj+='Usuario es requerido! ';
                        }
                         if(obj.company==''){
                            msj+='Empresa es requerido! ';
                        }
                         if(obj.nit==''){
                            msj+='Nit es requerido! ';
                        }
                    }
                   return msj;
                }
	}

});	/*end task*/

task.factory('Session',function($state,$ionicHistory){

	return {

		isLoggin:function(){
			
			 if (localStorage.getItem("token") === null) {
          		$state.go('login');
        	 }else{
        	 	if ($ionicHistory.currentView().stateName=="login") {
        	 		$state.go('home');
        	 	};
        	 }

		},
		logout:function(){
			localStorage.clear();
                        localStorage.removeItem('token');
                        $state.go('login');
		},
		doBack:function(){
			$ionicHistory.goBack();
		},
		
	}

});

}());


