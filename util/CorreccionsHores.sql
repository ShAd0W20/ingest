
/* Comprovació hores mòduls */

SELECT MP.modul_professional_id, MP.nom, MP.hores, SUM(UF.hores), IF(MP.hores=SUM(UF.hores), 'Ok', 'ERROR') 
FROM UNITAT_FORMATIVA UF 
LEFT JOIN MODUL_PROFESSIONAL MP ON (UF.modul_professional_id=MP.modul_professional_id)
GROUP BY UF.modul_professional_id;

SELECT MPE.modul_pla_estudi_id, MPE.nom, MPE.hores, SUM(UPE.hores), IF(MPE.hores=SUM(UPE.hores), 'Ok', 'ERROR') 
FROM UNITAT_PLA_ESTUDI UPE 
LEFT JOIN MODUL_PLA_ESTUDI MPE ON (UPE.modul_pla_estudi_id=MPE.modul_pla_estudi_id)
GROUP BY UPE.modul_pla_estudi_id;


/* Correcció hores mòduls */

UPDATE MODUL_PROFESSIONAL MP SET MP.hores=(SELECT SUM(UF.hores) FROM UNITAT_FORMATIVA UF WHERE UF.modul_professional_id=MP.modul_professional_id);

UPDATE MODUL_PLA_ESTUDI MPE SET MPE.hores=IFNULL((SELECT SUM(UPE.hores) FROM UNITAT_PLA_ESTUDI UPE WHERE UPE.modul_pla_estudi_id=MPE.modul_pla_estudi_id), 0);


/* Correcció hores setmanals mòdul */

UPDATE MODUL_PROFESSIONAL SET hores_setmana=hores/33 WHERE es_fct=0 OR es_fct IS NULL;
UPDATE MODUL_PROFESSIONAL SET hores_setmana=NULL WHERE nom LIKE '%centres de treball%';

UPDATE MODUL_PLA_ESTUDI SET hores_setmana=hores/33 WHERE es_fct=0 OR es_fct IS NULL;
UPDATE MODUL_PLA_ESTUDI SET hores_setmana=NULL WHERE nom LIKE '%centres de treball%';