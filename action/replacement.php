<?php
/**
 * Approve Plus
 *
 * @license    GPL2
 * @author     Gero Gothe <practical@medizin-lernen.de>
 */


# must be run within Dokuwiki
if(!defined('DOKU_INC')) die();


class action_plugin_approveplus_replacement extends DokuWiki_Action_Plugin {

    /**
     * Register callbacks
     */
    public function register(Doku_Event_Handler $controller) {
		
        $plist = plugin_list();
        
        if (in_array('dw2pdf',$plist) && in_array('approve',$plist)) # Both must be installed
            $controller->register_hook('PLUGIN_DW2PDF_REPLACE', 'BEFORE', $this, 'replacement_before');
				
    }
    
    
    
    function replacement_before(Doku_Event $event, $param) {
		global $INFO;
        global $auth;

        /** @var \helper_plugin_approve_db|null $db_helper */
        $db_helper = plugin_load('helper', 'approve_db');
        if (!$db_helper) return;

        $last_change_date = @filemtime(wikiFN($INFO['id']));
        $rev = !$INFO['rev'] ? $last_change_date : $INFO['rev'];
        $approve = $db_helper->getPageRevision($INFO['id'], (int) $rev);
        if (!$approve) return;

        $fallbackApprover = $this->getLang('not_approve_text');
        $fallbackDate = $this->getLang('DATE_text');
        $fallbackRevision = $this->getLang('REVISION_text');
        $fallbackRfa = $this->getLang('RFA_text');

        $event->data['replace']['@APPROVE_DATE@'] = $fallbackDate;
        $event->data['replace']['@REVISION@'] = $fallbackRevision;
        $event->data['replace']['@RFA@'] = $fallbackRfa;

        $rfaBy = $approve['ready_for_approval_by'] ?? '';
        if ($rfaBy !== '') {
            $rfaData = $auth->getUserData($rfaBy);
            $rfaName = $rfaData['name'] ?? $rfaBy;
            if ($rfaName !== '') {
                $event->data['replace']['@RFA@'] = $rfaName;
            }
        }

        if (($approve['status'] ?? '') === 'approved') {
            $approvedBy = $approve['approved_by'] ?? '';
            $data = $auth->getUserData($approvedBy);
            $name = $data['name'] ?? $approvedBy;
            $event->data['replace']['@APPROVER@'] = $name
                ? $this->getLang('approve_text') . $name
                : $fallbackApprover;

            $approvedDate = $approve['approved'] ?? null;
            if ($approvedDate) {
                $ts = strtotime($approvedDate);
                if ($ts !== false) {
                    $event->data['replace']['@APPROVE_DATE@'] = date('Y-m-d H:i:s', $ts);
                }
            }

            $version = $approve['version'] ?? null;
            if ($version !== null && $version !== '') {
                $event->data['replace']['@REVISION@'] = (string) $version;
            }

        } else {
            $event->data['replace']['@APPROVER@'] = $fallbackApprover;
        }
	}

}

//Setup VIM: ex: et ts=4 enc=utf-8 :
