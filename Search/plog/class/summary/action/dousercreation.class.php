<?php

	include_once( PLOG_CLASS_PATH."class/summary/action/registeraction.class.php" );
    include_once( PLOG_CLASS_PATH."class/data/validator/emailvalidator.class.php" );
    include_once( PLOG_CLASS_PATH."class/data/validator/stringvalidator.class.php" );
    include_once( PLOG_CLASS_PATH."class/data/validator/usernamevalidator.class.php" );
    include_once( PLOG_CLASS_PATH."class/data/validator/passwordvalidator.class.php" );    
    include_once( PLOG_CLASS_PATH."class/dao/users.class.php" );
    include_once( PLOG_CLASS_PATH."class/summary/view/doblogregistrationview.class.php" );

    /**
     * starts the user and blog registration process
     */
    class doUserCreation extends RegisterAction 
	{
	
		function doUserCreation( $actionInfo, $request )
		{
			$this->RegisterAction( $actionInfo, $request );
			
			// data validation and stuff like that :)
			$this->registerFieldValidator( "userName", new UsernameValidator());
			$this->registerFieldValidator( "userPassword", new PasswordValidator());
			$this->registerFieldValidator( "userPasswordCheck", new PasswordValidator());
			$this->registerFieldValidator( "userEmail", new EmailValidator());
			$this->registerField( "userFullName" );
			$view = new SummaryView( "registerstep1" );
			$view->setErrorMessage( $this->_locale->tr("error_adding_user" ));
			$this->setValidationErrorView( $view );
		}	

        function perform()
        {
	        // if all data is correct, then we can proceed and use it
			$tf = new Textfilter();	        
            $this->userName = $tf->filterAllHTML($this->_request->getValue( "userName" ));
            $this->userPassword = $tf->filterAllHTML($this->_request->getValue( "userPassword" ));
            $this->confirmPassword = $tf->filterAllHTML($this->_request->getValue( "userPasswordCheck" ));
            $this->userEmail = $tf->filterAllHTML($this->_request->getValue( "userEmail" ));
			$this->userFullName = $tf->filterAllHTML($this->_request->getValue( "userFullName" ));
			
			// check if there is already a user with the same username and quit if so
			$users = new Users();
			if( $users->userExists( $this->userName )) {
				$this->_view = new SummaryView( "registerstep1" );
				//$this->_form->hasRun( true );
				$this->_form->setFieldValidationStatus( "userName", false );
				$this->setCommonData( true );
				return false;
			}

            // check if this email account has registered and quit if so, but only if the configuration
		// says that we should only allow one blog per email account
		if( $this->_config->getValue( "force_one_blog_per_email_account" )) {
            if( $users->emailExists($this->userEmail)) {
				$this->_view = new SummaryView( "registerstep1" );
				//$this->_form->hasRun( true );
				$this->_form->setFieldValidationStatus( "userEmail", false );
				$this->setCommonData( true );
				return false;
            }
		}
			
			// check if the passwords match, and stop processing if so too
            if( $this->userPassword != $this->confirmPassword ) {
	            $this->_view = new SummaryView( "registerstep1" );
                $this->_view->setErrorMessage( $this->_locale->tr("error_passwords_dont_match"));
				$this->_form->setFieldValidationStatus( "userPasswordCheck", false );                
                $this->setCommonData( true );
                return false;
            }			


            // if everything went fine, then proceed
            $this->_view = new doBlogRegistrationView();
            $this->setValues();
            $this->setCommonData();
            return true;
        }

    }
?>
