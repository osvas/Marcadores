-- Doctrine Migration File Generated on 2020-05-30 06:57:57

-- Version 20200530064937
ALTER TABLE marcador ADD creado DATETIME NOT NULL;
INSERT INTO migration_versions (version, executed_at) VALUES ('20200530064937', CURRENT_TIMESTAMP);
