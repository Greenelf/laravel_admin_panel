<?php
namespace Greenelf\Panel;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{

    protected $table = 'links';

    static $cache = [];

    protected $allLinks;

    /**
     * @param bool $forceRefresh
     * @return mixed
     */
    public static function allCached($forceRefresh = false)
    {
        $admin = Admin::find((\Auth::guard('panel')->user()->id));

        if ($admin->hasRole('super')) {
            if (!isset(self::$cache['all']) || $forceRefresh) {
                self::$cache['all'] = Link::all();
            }
            return self::$cache['all'];
        } else {
            self::$cache['all'] = self::getPermissionLinks($forceRefresh);
        }
        return self::$cache['all'];
    }

    /**
     * @param $forceRefresh
     * @return mixed
     */
    public static function getPermissionLinks($forceRefresh){
        $admin = Admin::find((\Auth::guard('panel')->user()->id));
        $userRoles = $admin->roles->pluck('id')->toArray();
        if (count($userRoles) > 0) {
            if (count($userRoles) === 1) {
                $userPermissions = RolePermission::where('role_id', '=', $userRoles[0])->get();
            } else {
                $userPermissions = RolePermission::whereIn('role_id', $userRoles)->get();
            }
        }
        $accessModelsID = [];
        foreach ($userPermissions as $permission) {
            if ($permission->read === 1) {
                $accessModelsID[] = $permission->permission_id;
            }
        }
        if (!isset(self::$cache['all']) || $forceRefresh) {
            if (count($accessModelsID) > 0) {
                if (count($accessModelsID) === 1) {
                    //$all = Link::where('permission_id', '=', $accessModelsID[0])->get();
                    $collection = new Collection();
                    $collection->add(Permission::find($accessModelsID[0])->menuLink);
                }else{
                    $collection = Permission::whereIn('id', $accessModelsID)->get(['link_id']);
                    $collection = $collection->pluck('link_id')->toArray();
                    $collection = Link::whereIn('id', $collection)->orWhere('parent_id', '=', 0)->get();//Link::whereBetween('permission_id', $accessModelsID)->get();
                }
                self::$cache['all'] =  $collection;
            }
        }
        return self::$cache['all'];
    }

    /**
     * @param bool $forceRefresh
     * @return mixed
     */
    public static function returnUrls($forceRefresh = false)
    {

        if (!isset(self::$cache['all_urls']) || $forceRefresh) {
            Link::allCached($forceRefresh);
            $configs = self::$cache['all'];//Link::allCached($forceRefresh);
            self::$cache['all_urls'] = $configs->pluck('url')->toArray();
        }

        return self::$cache['all_urls'];
    }

    /**
     * @param bool $forceRefresh
     * @return mixed
     */
    public static function getMainUrls($forceRefresh = false)
    {

        if (!isset(self::$cache['main_urls']) || $forceRefresh) {
            //$configs = Link::where('parent_id', '=', 0)->get(['url']);
            $configs = Link::all();//where('parent_id', '=', 0)->get(['url']);
           // dd($configs->pluck('url')->toArray());
            self::$cache['main_urls'] = $configs->pluck('url')->toArray();
        }

        return self::$cache['main_urls'];
    }

    public static function getPanelModels()
    {
        $panelUrls = Link::where('is_panel_field', '=', 1)->get();
        $panelUrls = $panelUrls->pluck('url')->toArray();
        return $panelUrls;
    }

    public static function getModelUrls($forceRefresh = false)
    {

        if (!isset(self::$cache['main_urls']) || $forceRefresh) {
            //$configs = Link::where('parent_id', '=', 0)->get(['url']);
            $configs = Link::where('type', '=', 'model')->get(['url']);
            // dd($configs->pluck('url')->toArray());
            self::$cache['main_urls'] = $configs->pluck('url')->toArray();
        }

        return self::$cache['main_urls'];
    }

    /**
     * @param $url
     * @param $display
     */
    public function getAndSave($url, $display)
    {
        $this->url = $url;
        $this->display = $display;
        $this->save();
    }

    protected $fillable = array('url', 'display');

}
