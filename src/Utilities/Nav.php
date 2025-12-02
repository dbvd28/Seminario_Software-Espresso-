<?php

namespace Utilities;

class Nav
{
    public static function setPublicNavContext()
    {
        $tmpNAVIGATION = Context::getContextByKey("PUBLIC_NAVIGATION");
        if ($tmpNAVIGATION === "") {
            $navigationData = self::getNavFromJson()["public"];
            $saveToSession = intval(Context::getContextByKey("DEVELOPMENT")) !== 1;
            Context::setContext("PUBLIC_NAVIGATION", $navigationData, $saveToSession);
        }
    }
    public static function setNavContext()
    {
        $tmpNAVIGATION = Context::getContextByKey("NAVIGATION");
        $navUserId = Context::getContextByKey("NAV_USER_ID");
        $adminItems = [];
        $userItems = [];
        $editItems = [];
        $userID = Security::getUserId();
        $navigationData = self::getNavFromJson()["private"];
        // Construir NAVIGATION si aún no existe
      if (!is_array($tmpNAVIGATION) || count($tmpNAVIGATION) === 0 || $navUserId != $userID) {
            $tmpNAVIGATION = [];
            foreach ($navigationData as $navEntry) {
                if (
                    $navEntry["id"] === "Menu_User_Edit" || $navEntry["id"] === "Menu_Password_Edit" ||
                    Security::isAuthorized($userID, $navEntry["id"], 'MNU')
                ) {
                    if (isset($navEntry["nav_url"])) {
                        $navEntry["nav_url"] = str_replace("{userid}", $userID, $navEntry["nav_url"]);
                    }
                    $tmpNAVIGATION[] = $navEntry;
                }
            }
            $saveToSession = intval(Context::getContextByKey("DEVELOPMENT")) !== 1;
            Context::setContext("NAVIGATION", $tmpNAVIGATION, $saveToSession);
               Context::setContext("NAV_USER_ID", $userID, $saveToSession);
        }
        // Clasificar en grupos SIEMPRE a partir de NAVIGATION actual
        $finalNav = Context::getContextByKey("NAVIGATION");
        if (!is_array($finalNav) || count($finalNav) === 0) {
            $finalNav = $tmpNAVIGATION;
        }
        foreach ($finalNav as $navEntry) {
            if (!isset($navEntry["id"])) {
                continue;
            }
            if (strpos($navEntry["id"], "Menu_Administrator_") === 0) {
                $adminItems[] = $navEntry;
            } elseif (in_array($navEntry["id"], ["Menu_PaymentCheckout", "Menu_Client_Orders", "Menu_Client_Quejas"], true)) {
                $userItems[] = $navEntry;
            } elseif (in_array($navEntry["id"], ["Menu_User_Edit", "Menu_Password_Edit"], true)) {
                $editItems[] = $navEntry;
            }
        }
        $saveToSession = intval(Context::getContextByKey("DEVELOPMENT")) !== 1;
        Context::setContext("NAV_ADMIN", $adminItems, $saveToSession);
        Context::setContext("NAV_USER", $userItems, $saveToSession);
        Context::setContext("NAV_EDIT", $editItems, $saveToSession);
        Context::setContext("IS_ADMIN_MODE", count($adminItems) > 0, $saveToSession);
        Context::setContext("IS_USER_MODE", count($userItems)> 0, $saveToSession);
    }

    public static function invalidateNavData()
    {
        Context::removeContextByKey("NAVIGATION_DATA");
        Context::removeContextByKey("NAVIGATION");
        Context::removeContextByKey("PUBLIC_NAVIGATION");
    }

    private static function getNavFromJson()
    {
        $jsonContent = Context::getContextByKey("NAVIGATION_DATA");
        if ($jsonContent === "") {
            $filePath = 'nav.config.json';
            if (!file_exists($filePath)) {
                throw new \Exception(sprintf('%s does not exist', $filePath));
            }
            if (!is_readable($filePath)) {
                throw new \Exception(sprintf('%s file is not readable', $filePath));
            }
            $jsonContent = file_get_contents($filePath);
            $saveToSession = intval(Context::getContextByKey("DEVELOPMENT")) !== 1;
            Context::setContext("NAVIGATION_DATA", $jsonContent, $saveToSession);
        }
        $jsonData = json_decode($jsonContent, true);
        return $jsonData;
    }

    private function __construct()
    {
    }
    private function __clone()
    {
    }
}
