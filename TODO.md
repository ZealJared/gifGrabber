* Model ✓
* Category ✓
* Gif ✓
* API
  * Category
    * Create ✓
    * Read ✓
    * Update ✓
    * Delete ✓
    * Get GIFs (include unapproved, if admin) ✓
  * Gif
    * Create ✓
    * Read (admin guard unapproved) ✓
    * Update ✓
    * Delete ✓
    * hookAfterSave (no ID -- so no storage path -- before save) if Url changing (get asset from URL and save -- gif, mp4, preview jpg) ✓
    * hookBeforeDelete remove storage folder and contents for gif ✓
* Bug
  * Model does not correctly track changed status (update attempts to update all present fields) ✓
* UI
  * Admin login ✓
  * Store login credentials in localStorage
  * Category
    * List ✓
    * List GIFs ✓
    * Read ✓
    * Create
    * Update
    * Delete
  * Gif
    * Create
    * Read
    * Update
    * Delete
* Strategies
  * Create a strategy for each site in existing database GIFs
