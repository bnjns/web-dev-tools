<?php

namespace bnjns\WebDevTools\Laravel\Traits;

trait IsOrdered
{
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
                $currentOrder = $model->original[static::orderAttributeName()];
                $newOrder     = $model->{static::orderAttributeName()};

                if ($newOrder != $currentOrder) {
                    $increasing = $newOrder > $currentOrder;
                    if ($increasing) {
                        $to_move = static::whereBetween(static::orderAttributeName(), [$currentOrder + 1, $newOrder]);
                    } else {
                        $to_move = static::whereBetween(static::orderAttributeName(), [$newOrder, $currentOrder - 1]);
                    }

                    $to_move->where('id', '!=', $model->id)
                            ->get()
                            ->map(function ($m) use ($increasing) {
                                $m->processOrderOnSave = false;
                                $m->update([
                                    static::orderAttributeName() => $increasing
                                        ? ($m->{static::orderAttributeName()} - 1)
                                        : ($m->{static::orderAttributeName()} + 1),
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
            static::where(static::orderAttributeName(), '>', $model->{static::orderAttributeName()})
                  ->get()
                  ->map(function ($m) {
                      $m->processOrderOnSave = false;
                      $m->update([
                          static::orderAttributeName() => $m->{static::orderAttributeName()} - 1,
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
     * Get the attribute to use for ordering.
     *
     * @return string
     */
    protected static function orderAttributeName()
    {
        return isset(static::$orderAttributeName) ? static::$orderAttributeName : 'order';
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
        $query->orderBy(static::orderAttributeName(), 'ASC');
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
        $query->orderBy(static::orderAttributeName(), 'DESC');
    }

    /**
     * "Insert" the model in the order - this bumps everything that should be after by 1.
     *
     * @return void
     */
    public function insertIntoOrder()
    {
        if ($this->exists()) {
            static::where(static::orderAttributeName(), '>=', $this->{static::orderAttributeName()})
                  ->where('id', '!=', $this->id)
                  ->get()
                  ->map(function ($m) {
                      $m->processOrderOnSave = false;
                      $m->update([
                          static::orderAttributeName() => $m->{static::orderAttributeName()} + 1,
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
            if ($newOrder == $this->{static::orderAttributeName()} || $newOrder < 1 || $newOrder > static::count()) {
                return;
            }

            return $this->update([
                static::orderAttributeName() => $newOrder,
            ]);
        }
    }
}
