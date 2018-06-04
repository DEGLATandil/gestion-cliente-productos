<?php
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	#inicia la conexion con la base
	include("../db/api_db.php");
	include("../common/controlador.php");
	$db_manager = new DB();
	$controlador = new CONTROLADOR();
	$controlador->check_session();
	

	if(!empty($_POST['add_all'])){
		$w_cuit = 'scanner="'.$db_manager->clear_string($_POST['scanner']).'"';
		$total_cli = $db_manager->total('producto',$w_cuit);
		if($total_cli == 0){ 
			if($_POST['cat'] == 1){
				$categoria = $db_manager->clear_string($_POST['categoria_new']);
			}else{
				$categoria = $_POST['categoria'];
			}	
			$db_manager->add_producto($db_manager->clear_string($_POST['articulo']),$db_manager->clear_string($_POST['scanner']),$db_manager->clear_string($_POST['precio']),$db_manager->clear_string($_POST['iva']),$db_manager->clear_string($_POST['codbar']),$categoria);
			$controlador->set_msg(true,'El producto '.$db_manager->clear_string($_POST['nombre']).' fue agregado exitosamente');
			$controlador->set_session_expire();
			$db_manager->close_conexion();
			header('Location: ../prod/'); 
		}else{
			$controlador->set_error(true,'Existe un producto con el código ingresado.');
			$controlador->set_session_expire();
			$db_manager->close_conexion();
			header('Location: add.php'); 	
		}
	}else{
		if(!empty($_POST['editar'])){
			$where ='(scanner="'.$db_manager->clear_string($_POST['scanner']).'") AND NOT (id_producto='.$controlador->desencriptar($_POST['v']).')';
			$total_cli = $db_manager->total('producto',$where);
			if($total_cli == 0){ 
				if($_POST['cat'] == 1){
					$categoria = $db_manager->clear_string($_POST['categoria_new']);
				}else{
					$categoria = $_POST['categoria'];
				}
				$where = 'id_producto='.$controlador->desencriptar($_POST['v']);
				$element = 'articulo = "'.$db_manager->clear_string($_POST['articulo']).'", scanner="'.$db_manager->clear_string($_POST['scanner']).'", precio="'.$db_manager->clear_string($_POST['precio']).'", iva="'.$db_manager->clear_string($_POST['iva']).'"';
				$element .= ', codbar="'.$db_manager->clear_string($_POST['codbar']).'", categoria="'.$categoria.'"'; 
				$db_manager->update_one_element('producto',$element,$where);
				$controlador->set_msg(true,'El producto '.$db_manager->clear_string($_POST['articulo']).' fue editado exitosamente');
			}else{
				$controlador->set_error(true,'Existe un producto con el código ingresado.');
			}
			$controlador->set_session_expire();
			$db_manager->close_conexion();
			header('Location: det.php?v='.$_POST['v']);
		}else{	
			#Else final si no cumple nada
			$db_manager->close_conexion();
			header('Location: ../exit.php');
		}
	}
	
?> 

