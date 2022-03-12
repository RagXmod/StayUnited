<?php

namespace App\App\Facades;

/**
 * Module Api: App\App\Facades\UserAgentFacade
 *
 * Long description for class (if any)...
 *
 */

use Storage;

class UserAgentFacade
{

    public static function setAgentLists( $path = null) {

        if ( !$path ) {
            $storage = Storage::disk('public-path');
            if ( $storage->exists('json/agents.json') )
                $path = $storage->get('json/agents.json');
        }
        return $path;
    }

    /**
     * Grab a random user agent from the library's agent list
     *
     * @param  array $filterBy
     * @return string
     * @throws \Exception
     */
    public static function random($filterBy = [])
    {
        $agents = self::loadUserAgents($filterBy);

        if (empty($agents)) {
            throw new \Exception('No user agents matched the filter');
        }

        return $agents[mt_rand(0, count($agents) - 1)];
    }

    /**
     * Get all of the unique values of the device_type field, which can be used for filtering
     * Device types give a general description of the type of hardware that the agent is running,
     * such as "Desktop", "Tablet", or "Mobile"
     *
     * @return array
     */
    public static function getDeviceTypes()
    {
        return self::getField('device_type');
    }

    /**
     * Get all of the unique values of the agent_type field, which can be used for filtering
     * Agent types give a general description of the type of software that the agent is running,
     * such as "Crawler" or "Browser"
     *
     * @return array
     */
    public static function getAgentTypes()
    {
        return self::getField('agent_type');
    }

    /**
     * Get all of the unique values of the agent_name field, which can be used for filtering
     * Agent names are general identifiers for a given user agent. For example, "Chrome" or "Firefox"
     *
     * @return array
     */
    public static function getAgentNames()
    {
        return self::getField('agent_name');
    }

    /**
     * Get all of the unique values of the os_type field, which can be used for filtering
     * OS Types are general names given for an operating system, such as "Windows" or "Linux"
     *
     * @return array
     */
    public static function getOSTypes()
    {
        return self::getField('os_type');
    }

    /**
     * Get all of the unique values of the os_name field, which can be used for filtering
     * OS Names are more specific names given to an operating system, such as "Windows Phone OS"
     *
     * @return array
     */
    public static function getOSNames()
    {
        return self::getField('os_name');
    }

    /**
     * This is a helper for the publicly-exposed methods named get...()
     * @param  string $fieldName
     * @return array
     * @throws \Exception
     */
    private static function getField($fieldName)
    {
        $jsonPath     = self::setAgentLists();
        $agentDetails = json_decode($jsonPath, true);
        $values       = [];

        foreach ($agentDetails as $agent) {
            if (!isset($agent[$fieldName])) {
                throw new \Exception("Field name \"$fieldName\" not found, can't continue");
            }

            $values[] = $agent[$fieldName];
        }

        return array_values(array_unique($values));
    }

    /**
     * Validates the filter so that no unexpected values make their way through
     *
     * @param array $filterBy
     * @return array
     */
    private static function validateFilter($filterBy = [])
    {
        // Components of $filterBy that will not be ignored
        $filterParams = [
            'agent_name',
            'agent_type',
            'device_type',
            'os_name',
            'os_type',
        ];

        $outputFilter = [];

        foreach ($filterParams as $field) {
            if (!empty($filterBy[$field])) {
                $outputFilter[$field] = $filterBy[$field];
            }
        }

        return $outputFilter;
    }

    /**
     * Returns an array of user agents that match a filter if one is provided
     *
     * @param array $filterBy
     * @return array
     */
    private static function loadUserAgents($filterBy = [])
    {
        $filterBy = self::validateFilter($filterBy);

        $jsonPath     = self::setAgentLists();
        $agentDetails = json_decode($jsonPath, true);

        $agentStrings = [];

        for ($i = 0; $i < count($agentDetails); $i++) {
            foreach ($filterBy as $key => $value) {
                if (!isset($agentDetails[$i][$key]) || !self::inFilter($agentDetails[$i][$key], $value)) {
                    continue 2;
                }
            }
            $agentStrings[] = $agentDetails[$i]['agent_string'];
        }

        return array_values($agentStrings);
    }

    /**
     * return if key exist in array of filters
     *
     * @param  $key
     * @param  $array
     * @return bool
     */
    private static function inFilter($key, $array)
    {
        return in_array(strtolower($key), array_map('strtolower', (array) $array));
    }
}
