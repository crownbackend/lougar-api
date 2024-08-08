--
-- PostgreSQL database dump
--

-- Dumped from database version 16.3 (Debian 16.3-1.pgdg120+1)
-- Dumped by pg_dump version 16.3 (Debian 16.3-1.pgdg120+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: user; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public."user" (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    is_actif boolean NOT NULL,
    deleted_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    email character varying(180) NOT NULL,
    roles json NOT NULL,
    password character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL,
    birthday timestamp(0) without time zone NOT NULL,
    phone character varying(255) NOT NULL,
    validation_token character varying(255) DEFAULT NULL::character varying,
    created_at_validate_token timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public."user" OWNER TO root;

--
-- Name: COLUMN "user".id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public."user".id IS '(DC2Type:uuid)';


--
-- Name: COLUMN "user".created_at; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public."user".created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN "user".updated_at; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public."user".updated_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN "user".deleted_at; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public."user".deleted_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN "user".birthday; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public."user".birthday IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN "user".created_at_validate_token; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public."user".created_at_validate_token IS '(DC2Type:datetime_immutable)';


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: root
--

INSERT INTO public."user" VALUES ('018fd5ee-7fce-7120-a11b-4d910378715b', '2024-06-01 22:32:07', '2024-07-13 15:23:40', true, NULL, 'admin@live.fr', '["ROLE_ADMIN", "ROLE_OWNER", "ROLE_TENANT"]', '$2y$13$LHWYc9NCbmuo9sxzGnz.guw65sQhecMIN68WUvapqbhdqabALGXCy', 'Belha', 'bouchoucha', '1997-05-03 00:00:00', '0123456789', NULL, NULL);
INSERT INTO public."user" VALUES ('01901288-944f-75d9-b32d-75d4746341ab', '2024-06-13 16:57:38', NULL, true, NULL, 'nabile333@gmail.com', '["ROLE_OWNER"]', '$2y$13$vmH4opgmjhFlzP336KApvuF2xrUvNB15co4KrxxjVS9SKu0f/91vW', 'Yasmin', 'Bakouche', '1995-02-06 00:00:00', '0668031402', NULL, NULL);
INSERT INTO public."user" VALUES ('01901290-1731-7b17-a5dd-0333137beec2', '2024-06-13 17:05:50', NULL, true, NULL, 'bouchoucha.b.97@gmail.com', '["ROLE_TENANT"]', '$2y$13$GgCHgn30CXC1BL1lzbvMDevpbK/5Sv3pnB4XXXCePsgnmuYJ/azBK', 'Houcemeddine', 'Barkaoui', '2000-01-10 00:00:00', '0767698805', NULL, NULL);
INSERT INTO public."user" VALUES ('0190a414-89a5-779f-99d2-172de1ed624a', '2024-07-11 23:15:27', NULL, true, NULL, 'john1@doe.fr', '["ROLE_OWNER"]', '$2y$13$Jod9to1aGQfFbOuN2TbGke6.tY4l1jiYKuUOIiAfTzncdfd4DPCeG', 'john', 'doe', '1996-04-01 00:00:00', '0767698805', NULL, NULL);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: uniq_identifier_email; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX uniq_identifier_email ON public."user" USING btree (email);


--
-- PostgreSQL database dump complete
--

