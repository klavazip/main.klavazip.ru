<?
# При регистрации нового пользователя, отправляем его в 1с где создается новый партнер в ответ получаем xml 
# и записываем xml_id пользователя, при удалении пользователя из базы 1с он не удаляется но помечается как не активный
//AddEventHandler('main', 'OnAfterUserAdd', 	  array('Klava1CExportUser', 'addActionAddUser'));
//AddEventHandler('main', 'OnAfterUserUpdate',  array('Klava1CExportUser', 'addActionUpdateUser'));
//AddEventHandler('main', 'OnBeforeUserDelete', array('Klava1CExportUser', 'addActionDeleteUser'));