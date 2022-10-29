<?phpif( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

remove_action("tks_get_product_url");
remove_action("tks_username");
remove_action("tks_password");
remove_action("tks_societyId");
remove_action("tks_pointOfSaleId");
remove_action("tks_numberOfTheYear");
remove_action("tks_userId");
remove_action("tks_busAgencyName");
remove_action("tks_subject_name");
remove_action("tks_subject_name");
remove_action("tks_enable_disable_date");
remove_action("tks_description");


delete_transient( 'tks_times' );
delete_transient('tks_records');

?>