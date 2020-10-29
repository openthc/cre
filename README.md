# Cannabis/Crop Reporting Engine - CRE

This is a simple software solution for collecting and reporting on crops of cannabis or other tightly tracked, regulated products.
A simple setup of an event-object-audit-log database, along with the API end-point to collect the necessary details.

The platform is easy to extend by the regulatory agency or their selected vendor through middleware.

More information is available at https://openthc.com/

## Global Storage

 * Company
 * License
 * Contact
 * Variety

### License Specific Data

 * Section (aka: Area, Room)
 * Inventory Lot
 * Plant
 * Transfer (aka: Manifest, Invoice)

## Actions / Events

 * Plant/Create - Create Plants from Inventory Lot (of type: Plant, Clone or Seed)
 * Plant/Collect/Wet - Collect raw materials from the crop
 * Plant/Collect/Dry - Account for Raw materials Dry/Cured/Trimmed state.
 * Plant/Collect/Lot - Creates Production Inventory Lot
 * Lot/Create
 * Lot/Convert - One or more Source Lots into one Output Lot
 * Lot/Combine
 * Lot/Sample - Remove a Small Portion for Unique Sample (Employee, QA, Vendor, etc)
 * Transfer/Create - File the Transfer
 * Transfer/Commit - Commit/Send the Transfer
 * Transfer/Accept - Accept/Receive the Transfer
 * Transfer/Reject - Reject the Transfer

## Reporting

 * Realtime Object States
 * Realtime Activity Reports
 * Verbose Logging with complete Object delta
 * Easily extendable via Middleware components

## APIs

 * Simple [API Interface](https://api.openthc.org/)
 * Direct Write/Update of Objects (Plant, Lot, Transfer)
 * Indirect Update via Actions

## Identifiers

 * ULID - https://github.com/ulid/spec
 * No reliance on central authority (*BioTrack*, *METRC*, *LeafData*)
 * No reliance on expensive RFID (*METRC*)
 * No "Smart" numbers (*BioTrack*, *LeafData*)
 * Support for billions of billions of tracked items
   * Usable until 10889 AD
   * 1.21 Giga-Giga (2^80) unique items per millisecond!

## Customizing

If you want to add new custom Services, Middleware or other code use the `./Custom` directory.
All of your custom libraries would be in `./Custom/Service` or `./Custom/Middleware`.
Then you can use them in the `./webroot/front.php` script.
