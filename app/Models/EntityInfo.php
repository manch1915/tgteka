<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EntityInfo
 *
 * @property int $id
 * @property int $user_id
 * @property string $company_name
 * @property string $company_tax_id
 * @property string $legal_address
 * @property string $postal_address
 * @property string $kpp
 * @property string $checking_account
 * @property string $bic
 * @property string $correspondent_account
 * @property string $bank_name
 * @property string $contact_name
 * @property string $contact_phone
 * @property string $contact_email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\EntityInfoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereBic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereCheckingAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereCompanyTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereCorrespondentAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereKpp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereLegalAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo wherePostalAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntityInfo whereUserId($value)
 * @mixin \Eloquent
 */
class EntityInfo extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
