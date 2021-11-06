CREATE TABLE tx_tkcomposerserver_domain_model_account (
	username varchar(255) NOT NULL DEFAULT '',
	password varchar(255) NOT NULL DEFAULT '',
	all_repositories smallint(1) unsigned NOT NULL DEFAULT '0',
	repository_groups int(11) unsigned NOT NULL DEFAULT '0',
	repositories int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_tkcomposerserver_domain_model_repository (
	package_name varchar(255) NOT NULL DEFAULT '',
	url varchar(255) NOT NULL DEFAULT '',
	type int(11) DEFAULT '0' NOT NULL,
	access int(11) DEFAULT '0' NOT NULL,
	hash varchar(255) NOT NULL DEFAULT '',
	checksum varchar(255) NOT NULL DEFAULT '',
	data text NOT NULL DEFAULT ''
);

CREATE TABLE tx_tkcomposerserver_domain_model_repositorygroup (
	name varchar(255) NOT NULL DEFAULT '',
	repositories int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_tkcomposerserver_account_repositorygroup_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

CREATE TABLE tx_tkcomposerserver_account_repository_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

CREATE TABLE tx_tkcomposerserver_repositorygroup_repository_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

-- Add account relations and repository group relations on repository side
CREATE TABLE tx_tkcomposerserver_domain_model_repository (
	accounts int(11) unsigned DEFAULT '0' NOT NULL,
	repository_groups int(11) unsigned DEFAULT '0' NOT NULL
);

-- Add account relations on package group side
CREATE TABLE tx_tkcomposerserver_domain_model_repositorygroup (
	accounts int(11) unsigned DEFAULT '0' NOT NULL
);

-- Change column type to mediumblob
CREATE TABLE tx_tkcomposerserver_domain_model_repository (
    data mediumblob
);
