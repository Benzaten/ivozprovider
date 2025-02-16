.. _pbx_ddis:

####
DDIs
####

    Country
        The country of the new created DDI. Used for E164 standardization.

    DDI
        The number, without country code.

    Type
        Choose 'Inbound & outbound' for a normal DDI that can be used both as outgoing DDI and as incoming DDI
        from a :ref:`DDI Provider <DDI Providers>`. Choose 'Outbound only' for a DDI that won't reach us from a
        :ref:`DDI Provider <DDI Providers>` and will only be used as an outgoing DDI.

    DDI Provider
        The :ref:`DDI Provider <DDI Providers>` that provides this number. This relation has no functional purpose, it
        is just for DDI Provider <-> DDI navigation in some brand level sections.

    External Call Filter
        Allows configuration based on Calendars and Schedulers as shown in
        :ref:`External Call Filters`. Leave empty if you don't need to apply any
        kind of filter.

    Route
        A DDI can have different :ref:`treatments <routing_logics>`. For our
        current goal, set route to user and select *Alice*.

    Record calls
        Can be used to record external calls (see :ref:`call_recordings`).

    Tarificate incoming calls
        This setting requires the external tarification module and allows
        tarification on special numbers. This module is not standard so don't
        hesitate in :ref:`contact us <getting_help>` if you are interested.

.. hint:: Calls received from a :ref:`DDI Provider <DDI Providers>` to an 'Outbound only'
          DDI will be rejected.

.. hint:: Calls from within the platform to 'Inbound & outbound' DDIs won't reach
          any carrier, they will be routed internally.

********************
DDI external filters
********************

We can assign a **external call filter** configured in :ref:`External Call Filters`.


.. _routing_logics:

**********
DDI routes
**********

Once the call has passed all the checks in the filter (schedules and calendars)
and after the welcome locution has been played (if there is any configured),
we can route the call to the following processes:

- :ref:`users`
- :ref:`huntgroups`
- :ref:`ivrs`
- :ref:`conference_rooms`
- :ref:`conditional_routes`
- :ref:`queues`
- :ref:`friends`



.. hint:: We can also route the DDI to a :ref:`Virtual Fax <faxing_system>`, but
   this is something we will explain in the following block.
