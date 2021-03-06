<?php

	include_once( PLOG_CLASS_PATH."class/summary/action/summaryaction.class.php" );
    include_once( PLOG_CLASS_PATH."class/summary/view/summaryuserlistview.class.php" );    	
    include_once( PLOG_CLASS_PATH."class/data/validator/integervalidator.class.php" );   	

	/**
	 * shows a list with all the users, pager included
	 */
     class UserListAction extends SummaryAction
     {

        var $_numUsers;
		var $_numUsersPerPage;
		var $_page;

        function UserListAction( $actionInfo, $request )
        {
            $this->SummaryAction( $actionInfo, $request );
        }
		
        /**
         * Loads the posts and shows them.
         */
        function perform()
        {
            $page = View::getCurrentPageFromRequest();
			$this->_view = new SummaryUserListView( Array( "summary" => "UserList", 
			                                               "page" => $page,
			                                               "locale" => $this->_locale->getLocaleCode()));
			if( $this->_view->isCached()) {
				// nothing to do, the view is cached
				return true;
			}
			
			$this->setCommonData();
			
			return true;
		}
     }	 
?>