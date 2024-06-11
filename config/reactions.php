<?php

return [

    /*
     * This is the name of the table that will be created by the migration and
     * used by the Reaction model shipped with this package.
     */
    'table_name' => 'reactions',

    /**
     * The name of the model that will be used to represent
     * the model (eg. User) that is reacting to a reactable model (eg. Post).
     *
     * If this is set to null, the package will attempt to use
     * the default user model for your application.
     */
    'reacts_model' => null,

    /*
     * This is the name of the column on the "reactions" table that will be used to
     * identify the "reacts_model" relationship.
     *
     * If this is set to null, the package will attempt to use the "user_id" column
     * on the "Reaction" model.
     */
    'reacts_id_column' => null,
];
