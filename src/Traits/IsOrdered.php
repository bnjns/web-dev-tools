<?php

namespace bnjns\WebDevTools\Traits;

trait IsOrdered
{
    /**
     * Set the attribute to use for ordering.
     *
     * @var string
     */
    protected static $orderAttribute = 'order';

    /**
     * Sets whether the model should process the order change when it's saved.
     *
     * @var bool
     */
    protected $processOrderOnSave = true;

    /**
     * Register callbacks for the updating and deleted events.
     *
     * @return void
     */
    public static function boot()
    {
        /**
         * Hook into the updating event to re-order the list.
         */
        static::updating(function ($model) {
            if ($model->processOrderOnSave) {
                $currentOrder = $model->original[static::$orderAttribute];
                $newOrder     = $model->{static::$orderAttribute};

                if ($newOrder != $currentOrder) {
                    $increasing = $newOrder > $currentOrder;
                    if ($increasing) {
                        $to_move = static::whereBetween(static::$orderAttribute, [$currentOrder + 1, $newOrder])->get();
                    } else {
                        $to_move = static::whereBetween(static::$orderAttribute, [$newOrder, $currentOrder - 1])->get();
                    }

                    foreach ($to_move as $status) {
                        $status->processOrderOnSave = false;
                        $status->update([
                            static::$orderAttribute => $increasing ? ($status->{static::$orderAttribute} - 1) : ($status->{static::$orderAttribute} + 1),
                        ]);
                    }
                }
            }
            $model->processOrderOnSave = true;
        });

        /**
         * Hook into the deleted event to move any later items down.
         */
        static::deleted(function ($model) {
            $update = static::where(static::$orderAttribute, '>', $model->{static::$orderAttribute})->get();
            foreach ($update as $status) {
                $status->update([
                    static::$orderAttribute => $status->{static::$orderAttribute} - 1,
                ]);
            }
        });

        parent::boot();
    }

    /**
     * Add a scope to order the list.
     *
     * @param $query
     *
     * @return void
     */
    public function scopeOrdered($query)
    {
        $this->scopeOrderedAsc($query);
    }

    /**
     * Add a scope to order the list ascending.
     *
     * @param $query
     *
     * @return void
     */
    public function scopeOrderedAsc($query)
    {
        $query->orderBy(static::$orderAttribute, 'ASC');
    }

    /**
     * Add a scope to order the list descending.
     *
     * @param $query
     *
     * @return void
     */
    public function scopeOrderedDesc($query)
    {
        $query->orderBy(static::$orderAttribute, 'DESC');
    }

    /**
     * Move a driver status to a new position in the order.
     *
     * @param $newOrder
     *
     * @return bool
     */
    public function moveTo($newOrder)
    {
        $newOrder = (int)$newOrder;
        if ($newOrder == $this->{static::$orderAttribute} || $newOrder < 1 || $newOrder > static::count()) {
            return;
        }

        return $this->update([
            static::$orderAttribute => $newOrder,
        ]);
    }
}
