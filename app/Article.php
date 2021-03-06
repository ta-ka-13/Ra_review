<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// 追加
use Illuminate\Database\Eloquent\Relations\BelongsTo;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
// 追加
    protected $fillable = [
        'title',
        'body',
    ];
// 追加
// userメソッドの戻り値が、BelongsToクラス（型）であることを宣言しています。
    public function user(): BelongsTo
    {
        // $thisは、Articleクラスのインスタンス自身を指している
        // $this->メソッド名()とすることで、インスタンスが持つメソッドが実行され、
        // $this->プロパティ名とすることで、インスタンスが持つプロパティを参照します。
        return $this->belongsTo('App\User');
    }


    public function likes(): BelongsToMany
    {
        // いいねにおいてarticleモデルとuserモデルは多対多の関係
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }

    // 引数$userの型が、Userモデルであることを宣言している
    // ?を付けると、その引数がnullであることも許容している
    public function isLikedBy(?User $user): bool
    {

        return $user
        // $userがnullでなければ、結果をメソッドの呼び出し元に返す
            ? (bool)$this->likes->where('id', $user->id)->count()
            // $userがnullであれば、falseを返す
            : false;
    }

    public function getCountLikesAttribute(): int
    {
        // $this->likesにより、記事モデルからlikesテーブル経由で紐付いているユーザーモデルが、コレクション(配列を拡張したもの)で返る
        // countメソッドを使ってコレクションの要素数を数える
        return $this->likes->count();

    }

}