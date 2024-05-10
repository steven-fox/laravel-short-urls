<?php

namespace StevenFox\Larashurl\Resolvers\Visitors;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use StevenFox\Larashurl\Contracts\ResolvesVisitors;
use StevenFox\Larashurl\Events\ShortUrlVisited;

class QueryParsingVisitorResolver implements ResolvesVisitors
{
    public function resolveVisitor(ShortUrlVisited $event): ?Model
    {
        $id = $event->request->query('visitor_id');
        $type = $event->request->query('visitor_type');

        if (! ($id && $type)) {
            return null;
        }

        $morphedModelClass = Relation::getMorphedModel($type) ?: $type;

        $model = new $morphedModelClass;

        if (! ($model instanceof Model)) {
            return null;
        }

        $keyName = $model->getKeyName();
        $model->{$keyName} = $id;

        return $model;
    }
}
