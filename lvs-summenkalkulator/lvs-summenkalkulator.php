<?php
/**
 * Plugin Name: LVS Kalkulator
 * Plugin URI: https://alfasolutions.at
 * Description: Plugin to sum up the cost fields of a certain user.
 * Version: 1.0
 * Author: Fabian Kneidinger
 * Author URI: https://alfasolutions.at
 */

add_shortcode('lvs-kostenkalkulator', 'kostenkalkulator');
function kostenkalkulator() {
	if (is_user_logged_in()) {
		$user = get_current_user_id();
		$fahrt1 = get_usermeta( $user, $meta_key = 'fahrtkosten1' );
		$fahrt2 = get_usermeta( $user, $meta_key = 'fahrtkosten2' );
		$tender1 = get_usermeta( $user, $meta_key = 'tender1' );
		$tender2 = get_usermeta( $user, $meta_key = 'tender2' );
		$tender3 = get_usermeta( $user, $meta_key = 'tender3' );
		$bier = get_usermeta( $user, $meta_key = 'bierkonsum' );
		$bezahlt = get_usermeta( $user, $meta_key = 'bezahlt' );
		
		if (is_null($fahrt1) || $fahrt1 == "") {
			$fahrt1 = 0;
		}
		if (is_null($fahrt2) || $fahrt2 == "") {
			$fahrt2 = 0;
		}
		if (is_null($tender1) || $tender1 == "") {
			$tender1 = 0;
		}
		if (is_null($tender2) || $tender2 == "") {
			$tender2 = 0;
		}
		if (is_null($tender3) || $tender3 == "") {
			$tender3 = 0;
		}
		if (is_null($bier) || $bier == "") {
			$bier = 0;
		}
		if (is_null($bezahlt) || $bezahlt == "") {
			$bezahlt = 0;
		}
		
		$saldo = ($tender1 + $tender2 + $tender3) + $bier - ($fahrt1 + $fahrt2) - $bezahlt;
		if (get_permalink () !== "https://lvs-ooe.at/benutzerprofil/") {
		return "<h6>Offener Saldo beträgt: ".number_format($saldo, 2)." €</h6>";
		}
	}
}

add_shortcode('lvs-quizkalkulator', 'quizkalkulator');
function quizkalkulator() {
	if (is_user_logged_in()) {
		$user = get_current_user_id();
		$tagesquiz1 = get_usermeta( $user, $meta_key = 'tagesquiz1' );
		$tagesquiz2 = get_usermeta( $user, $meta_key = 'tagesquiz2' );
		$tagesquiz3 = get_usermeta( $user, $meta_key = 'tagesquiz3' );
		$abschlussquiz = get_usermeta( $user, $meta_key = 'abschlussquiz' );
		
		if (is_null($tagesquiz1) || $tagesquiz1 == "") {
			$tagesquiz1 = 0;
		}
		if (is_null($tagesquiz2) || $tagesquiz2 == "") {
			$tagesquiz2 = 0;
		}
		if (is_null($tagesquiz3) || $tagesquiz3 == "") {
			$tagesquiz3 = 0;
		}
		if (is_null($abschlussquiz) || $abschlussquiz == "") {
			$abschlussquiz = 0;
		}
		$average = ($tagesquiz1 +  $tagesquiz2 +  $tagesquiz3 +  $abschlussquiz) / 4;
		if (get_permalink () !== "https://lvs-ooe.at/benutzerprofil/") {
		return "<h6>Durchschnittliche Quizleistung: ". number_format($average)."/100</h6>";
		}
	}
}
add_shortcode('lvs-chargierkalkulator', 'chargierkalkulator');
function chargierkalkulator() {
	if (is_user_logged_in()) {
		$user = get_current_user_id();
		$wenden = get_usermeta( $user, $meta_key = 'wenden' );
		$marschieren = get_usermeta( $user, $meta_key = 'marschieren' );
		$grundhaltung = get_usermeta( $user, $meta_key = 'grundhaltung' );
		$anabtreten = get_usermeta( $user, $meta_key = 'anabtreten' );
		$ausrichten = get_usermeta( $user, $meta_key = 'ausrichten' );
		$aufabfassen = get_usermeta( $user, $meta_key = 'aufabfassen' );
		$vergatterung = get_usermeta( $user, $meta_key = 'vergatterung' );
		$strecken = get_usermeta( $user, $meta_key = 'strecken' );
		$klinge = get_usermeta( $user, $meta_key = 'klinge' );
		
		if (is_null($wenden) || $wenden == "") {
			$wenden = 0;
		}
		if (is_null($marschieren) || $marschieren == "") {
			$marschieren = 0;
		}
		if (is_null($grundhaltung) || $grundhaltung == "") {
			$grundhaltung = 0;
		}
		if (is_null($anabtreten) || $anabtreten == "") {
			$anabtreten = 0;
		}
		if (is_null($ausrichten) || $ausrichten == "") {
			$ausrichten = 0;
		}
		if (is_null($aufabfassen) || $aufabfassen == "") {
			$aufabfassen = 0;
		}
		if (is_null($vergatterung) || $vergatterung == "") {
			$vergatterung = 0;
		}
		if (is_null($strecken) || $strecken == "") {
			$strecken = 0;
		}
		if (is_null($klinge) || $klinge == "") {
			$klinge = 0;
		}
		$chargieren = ($wenden + $marschieren + $grundhaltung + $anabtreten + $ausrichten + $aufabfassen + $vergatterung + $strecken + $klinge) / 9;
		if (get_permalink () !== "https://lvs-ooe.at/benutzerprofil/") {
		return "<h6>Durchschnittliche Chargierleistung: Note ". number_format($chargieren)."</h6>";
		}
	}
}