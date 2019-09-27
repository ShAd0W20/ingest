<?php

/** 
 * LibMatricula.php
 *
 * Llibreria d'utilitats per a la matriculaci�.
 *
 * @author Josep Ciberta
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

/**
 * Classe que encapsula les utilitats per al maneig de la matr�cula.
 */
class Matricula 
{
	/**
	* Connexi� a la base de dades.
	* @access public 
	* @var object
	*/    
	public $Connexio;

	/**
	* Usuari autenticat.
	* @access public 
	* @var object
	*/    
	public $Usuari;

	/**
	 * Constructor de l'objecte.
	 * @param object $conn Connexi� a la base de dades.
	 * @param object $user Usuari de l'aplicaci�.
	 */
	function __construct($con, $user) {
		$this->Connexio = $con;
		$this->Usuari = $user;
	}

	/**
	 * CreaMatricula
	 * Crea la matr�cula per a un alumne. Quan es crea la matr�cula:
	 *   1. Pel nivell que sigui, es creen les notes, una per cada UF d'aquell cicle
	 *   2. Si l'alumne �s a 2n, l'aplicaci� ha de buscar les que li han quedar de primer per afegir-les.
	 *
	 * @param integer $CursId Id del curs.
	 * @param integer $AlumneId Id de l'alumne.
	 * @param string $Grup Grup (cap, A, B, C).
	 * @param string $GrupTutoria Grup de tutoria.
	 * @return integer Valor de retorn: 0 Ok, -1 Alumne ja matriculat, -99 Error.
	 */
	public function CreaMatricula($Curs, $Alumne, $Grup, $GrupTutoria) {
		$SQL = " CALL CreaMatricula(".$Curs.", ".$Alumne.", '".$Grup."', '".$GrupTutoria."', @retorn)";

		if (Config::Debug)
			print $SQL.'<br>';		
		
		// Obtenci� de la variable d'un procediment emmagatzemat.
		// http://php.net/manual/en/mysqli.quickstart.stored-procedures.php
		if (!$this->Connexio->query("SET @retorn = -99") || !$this->Connexio->query($SQL)) {
			echo "CALL failed: (" . $this->Connexio->errno . ") " . $this->Connexio->error;
		}

		if (!($res = $this->Connexio->query("SELECT @retorn as _retorn"))) {
			echo "Fetch failed: (" . $this->Connexio->errno . ") " . $this->Connexio->error;
		}

		$row = $res->fetch_assoc();
		return $row['_retorn'];	
	}

	/**
	 * CreaMatriculaDNI
	 * Crea la matr�cula per a un alumne a partir del DNI.
	 *
	 * @param integer $CursId Id del curs.
	 * @param string $DNI DNI de l'alumne.
	 * @param string $Grup Grup (cap, A, B, C).
	 * @param string $GrupTutoria Grup de tutoria.
	 * @return integer Valor de retorn:
	 *    0 Ok.
	 *   -1 Alumne ja matriculat.
	 *   -2 DNI inexistent.
	 *  -99 Error.
	 */
	public function CreaMatriculaDNI(int $Curs, string $DNI, string $Grup, string $GrupTutoria): int {
		$SQL = " CALL CreaMatriculaDNI(".$Curs.", '".$DNI."', '".$Grup."', '".$GrupTutoria."', @retorn)";

		if (Config::Debug)
			print $SQL.'<br>';		
		
		// Obtenci� de la variable d'un procediment emmagatzemat.
		// http://php.net/manual/en/mysqli.quickstart.stored-procedures.php
		if (!$this->Connexio->query("SET @retorn = -99") || !$this->Connexio->query($SQL)) {
			echo "CALL failed: (" . $this->Connexio->errno . ") " . $this->Connexio->error;
		}

		if (!($res = $this->Connexio->query("SELECT @retorn as _retorn"))) {
			echo "Fetch failed: (" . $this->Connexio->errno . ") " . $this->Connexio->error;
		}

		$row = $res->fetch_assoc();
		return $row['_retorn'];	
	}
	
	/**
	 * Convalida una UF (no es pot desfer).
	 * Posa el camp convalidat de NOTES a cert, posa una nota de 5 i el camp convocat�ria a 0.
     * @param array Primera l�nia.
	 */
	public function ConvalidaUF(int $NotaId): string {
		$SQL = 'SELECT * FROM NOTES WHERE notes_id='.$NotaId;	
		$ResultSet = $this->Connexio->query($SQL);
		if ($ResultSet->num_rows > 0) {		
			$rsNota = $ResultSet->fetch_object();

			$SQL = 'UPDATE NOTES SET convalidat=1 WHERE notes_id='.$NotaId;	
			$this->Connexio->query($SQL);

			$SQL = 'UPDATE NOTES SET nota'.$rsNota->convocatoria.'=5 WHERE notes_id='.$NotaId;	
			$this->Connexio->query($SQL);

			$SQL = 'UPDATE NOTES SET convocatoria=0 WHERE notes_id='.$NotaId;	
			$this->Connexio->query($SQL);
		}
	}

	/**
	 * Obt� l'identificador de l'alumne donada una matr�cula.
     * @param int $MatriculaId Identificador de la matr�cula.
	 * @return integer Identificador de l'alumne.
	 */
	public function ObteAlumne(int $MatriculaId): int {
		$iRetorn = -1;
		$SQL = 'SELECT alumne_id FROM MATRICULA WHERE matricula_id='.$MatriculaId;	
		$ResultSet = $this->Connexio->query($SQL);
		if ($ResultSet->num_rows > 0) {		
			$rsMatricula = $ResultSet->fetch_object();
			$iRetorn = $rsMatricula->alumne_id;
		}
		return $iRetorn;
	}
}

 ?>