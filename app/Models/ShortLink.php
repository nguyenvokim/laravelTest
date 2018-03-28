<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Mockery\Exception;

/**
 * App\Models\ShortLink
 *
 * @property int $id
 * @property int $view_count
 * @property string $link_code
 * @property string $link_redirect
 * @property int $user_id
 * @property int $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortLink whereLinkCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortLink whereLinkRedirect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortLink whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortLink whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortLink whereViewCount($value)
 * @mixin \Eloquent
 */
class ShortLink extends Model
{
    protected $table = 'short_links';
    protected $fillable = [
        'view_count',
        'link_code',
        'link_redirect',
        'user_id',
        'status'
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVE = 1;
    const LIMIT = 20;

    public function addViewCount() {
        //Will bug when handle concurrency request
        $this->view_count = $this->view_count + 1;
        $this->save();
    }

    /**
     * @param $request Request
     * @return ShortLink
     */
    public static function createFromRequest($request) {
        /**
         * @var $shortLink ShortLink
         */
        $id = $request->get('id', 0);
        if ($id) {
            $shortLink = ShortLink::findOrFail($id);
            if ($shortLink->user_id != \Auth::id()) {
                throw new Exception('Not allow');
            }
            $shortLink->link_redirect = $request->get('link_redirect');
            $shortLink->status = $request->get('status', self::STATUS_ACTIVE);
            $shortLink->save();
        } else {
            $shortLink = ShortLink::create([
                'view_count' => 0,
                'link_code' => self::generateUniqueShortLink(),
                'link_redirect' => $request->get('link_redirect'),
                'user_id' => \Auth::id(),
                'status' => $request->get('status', self::STATUS_ACTIVE)
            ]);

        }
        return $shortLink;
    }

    public static function generateUniqueShortLink() {
        $str = bin2hex(random_bytes(7));
        $picked = false;
        while (!$picked) {
            $existLink = ShortLink::whereLinkCode($str)->first();
            if ($existLink) {
                $str = bin2hex(random_bytes(7));
            } else {
                $picked = true;
            }
        }
        return $str;
    }
    public static function getListLinkByUser($page) {
        return ShortLink::whereUserId(\Auth::id())->orderBy('id', 'DESC')->paginate(self::LIMIT, ['*'], 'page', $page);
    }

}
