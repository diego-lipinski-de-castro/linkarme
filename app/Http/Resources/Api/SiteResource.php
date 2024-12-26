<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Site $this */

        return [
            "id" => $this->id,
            "url" => $this->url,
            "name" => $this->name,
            "description" => $this->description,
            "obs" => $this->obs,
            "da" => $this->da,
            "dr" => $this->dr,
            "traffic" => $this->traffic,
            "tf" => $this->tf,
            "category_id" => $this->category_id,
            "language_id" => $this->language_id,
            "country_id" => $this->country_id,
            "link_type" => $this->link_type,
            "gambling" => $this->gambling,
            "cdb" => $this->cdb,
            "cripto" => $this->cripto,
            "sponsor" => $this->sponsor,
            "broken" => $this->broken,
            "ssl" => $this->ssl,
            "cost" => $this->cost,
            "sale" => $this->sale,
            "cost_coin" => $this->cost_coin,
            "sale_coin" => $this->sale_coin,
            "last_posted" => $this->last_posted,
            "inserted_at" => $this->inserted_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "deleted_at" => $this->deleted_at,
            "seller_id" => $this->seller_id,
            "status" => $this->status,
            "deleted_why" => $this->deleted_why,
            "suggested" => $this->suggested,
            "sale_updated_at" => $this->sale_updated_at,
            "menu" => $this->menu,
            "banner" => $this->banner,
            "owner_name" => $this->owner_name,
            "owner_email" => $this->owner_email,
            "owner_phone" => $this->owner_phone,
            "bank" => $this->bank,
            "pix" => $this->pix,
            "phone" => $this->phone,
            "paypal" => $this->paypal,
            "instagram" => $this->instagram,
            "facebook" => $this->facebook,
            "last_updated_at" => $this->last_updated_at,
            "team_id" => $this->team_id,
            "owner_role" => $this->owner_role,
            "promo" => $this->promo,
            "restrict" => $this->restrict,
            "restrict_detail" => $this->restrict_detail,
            "archive_article" => $this->archive_article,
            "archive_due" => $this->archive_due,
            "links" => $this->links,
            "embed" => $this->embed,
            "images" => $this->images,
            "example_article" => $this->example_article,
            "rules" => $this->rules,
            "google_news" => $this->google_news,
            "global_account" => $this->global_account,
            "formatted_status" => $this->formatted_status,
            "formatted_inserted_at" => $this->formatted_inserted_at,
            "formatted_last_updated_at" => $this->formatted_last_updated_at,
            "formatted_updated_at" => $this->formatted_updated_at,
            "real_url" => $this->real_url,
        ];
    }
}
