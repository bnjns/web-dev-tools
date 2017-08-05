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
         * Hook into the created event to fix the order.
         */
        static::created(function ($model) {
            $model->insertIntoOrder();
        });

        /**
         * Hook into the updating event to re-order the list.
         */
        static::updated(function ($model) {
            if ($model->processOrderOnSave) {
                $currentOrder = $model->original[static::$orderAttribute];
                $newOrder     = $model->{static::$orderAttribute};

                if ($newOrder != $currentOrder) {
                    $increasing = $newOrder > $currentOrder;
                    if ($increasing) {
                        $to_move = static::whereBetween(static::$orderAttribute, [$currentOrder + 1, $newOrder]);
                    } else {
                        $to_move = static::whereBetween(static::$orderAttribute, [$newOrder, $currentOrder - 1]);
                    }

                    $to_move->get()
                            ->map(function ($m) use ($increasing) {
                                $m->processOrderOnSave = false;
                                $m->update([
                                    static::$orderAttribute => $increasing
                                        ? ($m->{static::$orderAttribute} - 1)
                                        : ($m->{static::$orderAttribute} + 1),
                                ]);
                            });
                }
            }
            $model->processOrderOnSave = true;
        });

        /**
         * Hook into the deleted event to move any later items down.
         */
        static::deleted(function ($model) {
            static::where(static::$orderAttribute, '>', $model->{static::$orderAttribute})
                  ->get()
                  ->map(function ($m) {
                      $m->processOrderOnSave = false;
                      $m->update([
                          static::$orderAttribute => $m->{static::$orderAttribute} - 1,
                      ]);
                  });
        });

        /**
         * Hook into the restore event to restore the correct order.
         */
        static::restored(function ($model) {
            $model->insertIntoOrder();
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
     * "Insert" the model in the order - this bumps everything that should be after by 1.
     *
     * @return void
     */
    public function insertIntoOrder()
    {
        if ($this->exists()) {
            static::where(static::$orderAttribute, '>=', $this->{static::$orderAttribute})
                  ->where('id', '!=', $this->id)
                  ->get()
                  ->map(function ($m) {
                      $m->processOrderOnSave = false;
                      $m->update([
                          static::$orderAttribute => $m->{static::$orderAttribute} + 1,
                      ]);
                  });
        }
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
        if ($this->exists()) {
            $newOrder = (int)$newOrder;
            if ($newOrder == $this->{static::$orderAttribute} || $newOrder < 1 || $newOrder > static::count()) {
                return;
            }

            return $this->update([
                static::$orderAttribute => $newOrder,
            ]);
        }
    }
}
