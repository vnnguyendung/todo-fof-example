CREATE TABLE IF NOT EXISTS "#__todo_items" (
  "todo_item_id" serial NOT NULL,
  "title" character varying(255) NOT NULL,
  "slug" character varying(50) NOT NULL,
  "description" text,
  "due" timestamp without time zone DEFAULT "1970-01-01 00:00:00" NOT NULL,
  "enabled" smallint DEFAULT 1 NOT NULL,
  "ordering" bigint DEFAULT 0 NOT NULL,
  "asset_id" bigint DEFAULT 0 NOT NULL,
  "created_by" bigint DEFAULT 0 NOT NULL,
  "created_on" timestamp without time zone DEFAULT "1970-01-01 00:00:00" NOT NULL,
  "modified_by" bigint DEFAULT 0 NOT NULL,
  "modified_on" timestamp without time zone DEFAULT "1970-01-01 00:00:00" NOT NULL,
  "locked_by" bigint DEFAULT 0 NOT NULL,
  "locked_on" timestamp without time zone DEFAULT "1970-01-01 00:00:00" NOT NULL,
  PRIMARY KEY ("todo_item_id")
);

SELECT nextval('#__todo_items_todo_item_id_seq');
SELECT setval('#__todo_items_todo_item_id_seq', 1, false);
