<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DocumentData Entity
 *
 * @property int $id
 * @property int $document_id
 * @property string $language
 * @property string $title
 * @property string $text
 * @property bool $is_draft
 *
 * @property \App\Model\Entity\Document $document
 */
class DocumentData extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
