<?php

namespace App;

enum ReactionEnum: string
{
   case LIKE = 'like';
   case DISLIKE = 'dislike';
   case LOVE = 'love';
   case SAD = 'sad';
   case WOW = 'wow';
   case ANGRY = 'angry';
}
