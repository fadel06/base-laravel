<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class MenuHelper
{
    public static function canAccess($path)
    {
        $permissionMap = [
            '/activity-logs' => 'view-activity-logs',
            '/permissions'   => 'view-permissions',
            '/roles'         => 'view-roles',
            '/regions'       => 'view-regions',
            '/opds'          => 'view-opds',
            '/users'         => 'view-users',
            '/bidang-dinas'  => 'view-bidang-dinas',
        ];

        $permission = $permissionMap[$path] ?? null;

        if (!$permission) {
            return true;
        }

        return auth()->check() && auth()->user()->can($permission);
    }

    public static function getMainNavItems()
    {
        return [
            [
                'icon' => 'dashboard',
                'name' => 'Dashboard',
                'path' => '/',
            ],
            [
                'icon' => 'calendar',
                'name' => 'Calendar',
                'path' => '/calendar',
            ],
        ];
    }

    public static function getMasterDataItems()
    {
        return [
            [
                'icon' => 'bidang',
                'name' => 'Bidang Dinas',
                'path' => '/bidang-dinas',
            ],
            [
                'icon' => 'building',
                'name' => 'OPD',
                'path' => '/opds',
            ],
            [
                'icon' => 'pin',
                'name' => 'Wilayah',
                'path' => '/regions',
            ],
        ];
    }

    public static function getUserManagementItems()
    {
        return [
            [
                'icon' => 'user',
                'name' => 'User',
                'path' => '/users',
            ],
            [
                'icon' => 'role',
                'name' => 'Peran',
                'path' => '/roles',
            ],
            [
                'icon' => 'permission',
                'name' => 'Hak Akses',
                'path' => '/permissions',
            ],
        ];
    }

    public static function getMonitoringItems()
    {
        return [
            [
                'icon' => 'activity-log',
                'name' => 'Activity Logs',
                'path' => '/activity-logs',
            ],
        ];
    }

    public static function getMenuGroups()
    {
        $groups = [];

        $mainNavItems = [];
        foreach (self::getMainNavItems() as $item) {
            if (!isset($item['path']) || self::canAccess($item['path'])) {
                $mainNavItems[] = $item;
            }
        }
        if (!empty($mainNavItems)) {
            $groups[] = [
                'title' => '',
                'items' => $mainNavItems,
            ];
        }

        $masterDataItems = [];
        foreach (self::getMasterDataItems() as $item) {
            if (!isset($item['path']) || self::canAccess($item['path'])) {
                $masterDataItems[] = $item;
            }
        }
        if (!empty($masterDataItems)) {
            $groups[] = [
                'title' => 'Master Data',
                'items' => $masterDataItems,
            ];
        }

        $userMgmtItems = [];
        foreach (self::getUserManagementItems() as $item) {
            if (!isset($item['path']) || self::canAccess($item['path'])) {
                $userMgmtItems[] = $item;
            }
        }
        if (!empty($userMgmtItems)) {
            $groups[] = [
                'title' => 'User Management',
                'items' => $userMgmtItems,
            ];
        }

        $monitoringItems = [];
        foreach (self::getMonitoringItems() as $item) {
            if (!isset($item['path']) || self::canAccess($item['path'])) {
                $monitoringItems[] = $item;
            }
        }
        if (!empty($monitoringItems)) {
            $groups[] = [
                'title' => 'Monitoring',
                'items' => $monitoringItems,
            ];
        }

        return $groups;
    }

    public static function isActive($basePath)
    {
        $currentPath = request()->path();
        $basePath    = ltrim($basePath, '/');

        if (!Str::endsWith($basePath, '/')) {
            $basePath .= '/';
        }

        return Str::startsWith($currentPath, $basePath) || $currentPath === rtrim($basePath, '/');
    }

    public static function getIconSvg($iconName)
    {
        $icons = [
            'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>',

            'building' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'bidang' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="10" y="2" width="4" height="3" rx="0.5"/><rect x="2" y="10" width="4" height="3" rx="0.5"/><rect x="10" y="10" width="4" height="3" rx="0.5"/><rect x="18" y="10" width="4" height="3" rx="0.5"/><rect x="2" y="18" width="4" height="3" rx="0.5"/><rect x="10" y="18" width="4" height="3" rx="0.5"/><path d="M12 5v5M12 8H4v2M12 8h8v2M4 13v5M12 13v5"/></svg>',

            'pin' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.25C8.27208 2.25 5.25 5.27208 5.25 9C5.25 10.8969 6.04769 12.6118 7.32107 13.8545L11.4697 17.9697C11.7626 18.2626 12.2374 18.2626 12.5303 17.9697L16.6789 13.8545C17.9523 12.6118 18.75 10.8969 18.75 9C18.75 5.27208 15.7279 2.25 12 2.25ZM3.75 9C3.75 4.44365 7.44365 0.75 12 0.75C16.5563 0.75 20.25 4.44365 20.25 9C20.25 11.3348 19.2643 13.4466 17.6934 14.9929L13.5303 19.0303C12.6892 19.8714 11.3108 19.8714 10.4697 19.0303L6.30661 14.9929C4.73566 13.4466 3.75 11.3348 3.75 9ZM12 7.25C10.7574 7.25 9.75 8.25736 9.75 9.5C9.75 10.7426 10.7574 11.75 12 11.75C13.2426 11.75 14.25 10.7426 14.25 9.5C14.25 8.25736 13.2426 7.25 12 7.25ZM8.25 9.5C8.25 7.42893 9.92893 5.75 12 5.75C14.0711 5.75 15.75 7.42893 15.75 9.5C15.75 11.5711 14.0711 13.25 12 13.25C9.92893 13.25 8.25 11.5711 8.25 9.5Z" fill="currentColor"/><path d="M7.25 20.5C7.25 20.0858 7.58579 19.75 8 19.75H16C16.4142 19.75 16.75 20.0858 16.75 20.5C16.75 20.9142 16.4142 21.25 16 21.25H8C7.58579 21.25 7.25 20.9142 7.25 20.5Z" fill="currentColor"/></svg>',

            'user' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z" fill="currentColor"></path></svg>',

            'role' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 9C16 11.2091 14.2091 13 12 13C9.79086 13 8 11.2091 8 9C8 6.79086 9.79086 5 12 5C14.2091 5 16 6.79086 16 9Z" stroke="currentColor" stroke-width="1.5"/><path d="M12 15C8.5 15 6 17 6 20H18C18 17 15.5 15 12 15Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M17 17H19C20.1046 17 21 17.8954 21 19C21 20.1046 20.1046 21 19 21H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',

            'permission' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L2 7V10C2 15.55 5.45 20.75 12 22C18.55 20.75 22 15.55 22 10V7L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M12 11V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="12" cy="8.5" r="1.5" fill="currentColor"/></svg>',

            'activity-log' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H18.5C19.7426 20.75 20.75 19.7426 20.75 18.5V5.5C20.75 4.25736 19.7426 3.25 18.5 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H18.5C18.9142 4.75 19.25 5.08579 19.25 5.5V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V5.5ZM7.25 8C7.25 7.58579 7.58579 7.25 8 7.25H16C16.4142 7.25 16.75 7.58579 16.75 8C16.75 8.41421 16.4142 8.75 16 8.75H8C7.58579 8.75 7.25 8.41421 7.25 8ZM7.25 12C7.25 11.5858 7.58579 11.25 8 11.25H16C16.4142 11.25 16.75 11.5858 16.75 12C16.75 12.4142 16.4142 12.75 16 12.75H8C7.58579 12.75 7.25 12.4142 7.25 12ZM7.25 16C7.25 15.5858 7.58579 15.25 8 15.25H12C12.4142 15.25 12.75 15.5858 12.75 16C12.75 16.4142 12.4142 16.75 12 16.75H8C7.58579 16.75 7.25 16.4142 7.25 16Z" fill="currentColor"/></svg>',

            'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C8.41421 2 8.75 2.33579 8.75 2.75V3.75H15.25V2.75C15.25 2.33579 15.5858 2 16 2C16.4142 2 16.75 2.33579 16.75 2.75V3.75H18.5C19.7426 3.75 20.75 4.75736 20.75 6V9V19C20.75 20.2426 19.7426 21.25 18.5 21.25H5.5C4.25736 21.25 3.25 20.2426 3.25 19V9V6C3.25 4.75736 4.25736 3.75 5.5 3.75H7.25V2.75C7.25 2.33579 7.58579 2 8 2ZM8 5.25H5.5C5.08579 5.25 4.75 5.58579 4.75 6V8.25H19.25V6C19.25 5.58579 18.9142 5.25 18.5 5.25H16H8ZM19.25 9.75H4.75V19C4.75 19.4142 5.08579 19.75 5.5 19.75H18.5C18.9142 19.75 19.25 19.4142 19.25 19V9.75Z" fill="currentColor"></path></svg>',
        ];

        return $icons[$iconName] ?? '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="currentColor"/></svg>';
    }
}
