<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Model\Post;
use App\Model\RelatedPost; 
$invalidRelations = RelatedPost::whereDoesntHave('post1')
                    ->orWhereDoesntHave('post2')
                    ->delete();

echo "تعداد {$invalidRelations} رابطه نامعتبر حذف شدند.";