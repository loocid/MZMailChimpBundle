<?php

/*
 * This file is part of the MZ\MailChimpBundle
 *
 * (c) Miguel Perez <miguel@mlpz.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MZ\MailChimpBundle\Services\Methods;

use MZ\MailChimpBundle\Services\HttpClient;

/**
 * Mailchimp List method
 *
 * @author Miguel Perez <miguel@mlpz.mp>
 * @link   http://apidocs.mailchimp.com/api/1.3/#listrelated
 */
class MCList extends HttpClient
{

    private $merge = null;
    private $emailType = 'html';
    private $doubleOptin = true;
    private $updateExisting = false;
    private $replaceInterests = true;
    private $sendWelcome = false;
    private $email;
    private $mergeVars = array();
    private $deleteMember = true;
    private $sendGoodbye = false;
    private $sendNotify = false;

    /**
     * Set mailchimp merge
     *
     * @param array $merge subscribe merge
     */
    public function setMerge(array $merge)
    {
        $this->merge = $merge;
    }

    /**
     * Set mailchimp email type
     *
     * @param string $emailType string to send email type
     */
    public function setEmailType($emailType)
    {
        $this->emailType = $emailType;
    }

    /**
     * Set mailchimp double optin
     *
     * @param boolean $doubleOptin boolen to double optin
     */
    public function setDoubleOption($doubleOptin)
    {
        $this->doubleOptin = $doubleOptin;
    }

    /**
     * Set mailchimp update existing
     *
     * @param boolean $updateExisting boolean to update user
     */
    public function setUpdateExisting($updateExisting)
    {
        $this->updateExisting = $updateExisting;
    }

    /**
     * Set mailchimp replace interests
     *
     * @param boolean $replaceInterests boolean to replace intersests
     */
    public function setReplaceInterests($replaceInterests)
    {
        $this->replaceInterests = $replaceInterests;
    }

    /**
     * Set mailchimp send welcome
     *
     * @param boolean $sendWelcome boolen to send welcome email
     */
    public function SendWelcome($sendWelcome)
    {
        $this->sendWelcome = $sendWelcome;
    }

	/**
	 * Set mailchimp send goodbye
	 * 
	 * @param boolean $sendGoodbye boolean to send goodbye email
	 */ 
	public function setSendGoodbye($sendGoodbye)
	{
		$this->sendGoodbye = $sendGoodbye;
	}

   /**
    * Set mailchimp merge_vars
    * 
    * @param unknown_type $email      Old user email
    * @param unknown_type $newEmail   New user email
    * @param unknown_type $groupings  User group
    * @param unknown_type $optionIP   User ip addres
    * @param unknown_type $optinTime  Subcribe time
    * @param unknown_type $mcLocation Use location
    */
    public function MergeVars($email = null, $newEmail = null, $groupings = null, $optionIP = null, $optinTime = null, $mcLocation = null)
    {
        $this->mergeVars['EMAIL'] = $email;
        $this->mergeVars['NEW-EMAIL'] = $newEmail;
        $this->mergeVars['GROUPINGS'] = $groupings;
        $this->mergeVars['OPTIN_IP'] = $optionIP;
        $this->mergeVars['OPTIN_TIME'] = $optinTime;
        $this->mergeVars['MC_LOCATION'] = $mcLocation;
        $this->mergeVars = array_merge($this->mergeVars, $this->merge);
    }

    /**
     * Set user email
     * 
     * @param string $email user email
     */

    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Subscribe member to list
     *
     * @param string $email user email
     *
     * @return boolen
     */
    public function Subscribe($email = null)
    {
        if (!empty($email)) {
            $this->email = $email;
        }

        $payload = array('email_address' => $this->email,
                'merge_vars' => $this->merge, 'email_type' => $this->emailType,
                'double_optin' => $this->doubleOptin,
                'update_existing' => $this->updateExisting,
                'replace_interests' => $this->replaceInterests,
                'send_welcome' => $this->sendWelcome,);

        $apiCall = 'listSubscribe';
        $data = $this->makeRequest($apiCall, $payload);
        $data = json_decode($data);

        return $data;
    }

    /**
     * Subscribe member to list
     *
     * @param string $email user email
     *
     * @return boolen
     */
    public function UnSubscribe($email)
    {

        $payload = array('email_address' => $email,
                'delete_member' => $this->deleteMember,
                'send_goodbye' => $this->sendGoodbye,
                'send_notify' => $this->sendNotify,);

        $apiCall = 'listUnsubscribe';
        $data = $this->makeRequest($apiCall, $payload);
        $data = json_decode($data);

        return $data;
    }

    /**
     * Update member
     * 
     * @return array
     */
    public function UpdateMember()
    {
        $payload = array('email_address' => $this->email,
                'merge_vars' => $this->mergeVars,
                'email_type' => $this->emailType,);

        $apiCall = 'listUpdateMember';
        $data = $this->makeRequest($apiCall, $payload);
        $data = json_decode($data);
        return $data;
    }
	
	/**
	* Get info about a member
	*
	* @return object
	*/
	public function getMemberInfo($email) 
	{
		$payload = array('email_address' => $email);
		$apiCall = 'listMemberInfo';
		$data = $this->makeRequest($apiCall, $payload);
		return json_decode($data);
	}
}
