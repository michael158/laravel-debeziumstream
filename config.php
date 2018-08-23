<?php

return [
   /*
   |--------------------------------------------------------------------------
   | Handle Class
   |--------------------------------------------------------------------------
   | The class who will receive Kafka Topic Messages
   */
   'handler_class' => 'your class here',

   /*
   |--------------------------------------------------------------------------
   | Handle Methods
   |--------------------------------------------------------------------------
   | The class methods who will receiver Kafka Events
   */
   'event_methods' => [
      'create' => 'your method here',
      'update' => 'your method here',
      'delete' => 'your method here'
   ]
];