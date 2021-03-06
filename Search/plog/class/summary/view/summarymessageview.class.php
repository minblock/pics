<?php

	include_once( PLOG_CLASS_PATH."class/summary/view/summaryview.class.php" );
	include_once( PLOG_CLASS_PATH."class/locale/locales.class.php" );

	/**
	 * shows a feedback (or error) message
	 */
    class SummaryMessageView extends SummaryView
	{

        function SummaryMessageView( $messageBody = "" )
        {
            $this->SummaryView( "message" );

			if( $messageBody != "" )
				$this->setSuccessMessage( $messageBody );
        }
    }
?>
