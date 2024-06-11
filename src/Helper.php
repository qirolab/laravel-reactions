<?php

namespace Qirolab\Laravel\Reactions;

class Helper
{
    /**
     * Retrieve User's model class name.
     *
     * @return string
     */
    public static function resolveReactsModel()
    {
        $userModel = config('reactions.reacts_model');
        if ($userModel) {
            return $userModel;
        }

        return config('auth.providers.users.model');
    }

    /**
     * Retrieve User's model column name in reactions table.
     *
     * @return string
     */
    public static function resolveReactsIdColumn()
    {
        $userModel = config('reactions.reacts_id_column');
        if ($userModel) {
            return $userModel;
        }

        return 'user_id';
    }
}
