-- SQL equivalente a las migraciones AddUserIdToProfesor y AddUserIdToAlumno

-- ----------------------------------------------------------------------
-- Versión "up"
-- ----------------------------------------------------------------------

ALTER TABLE `profesor`
    ADD COLUMN `user_id` INT UNSIGNED NULL AFTER `id_profesor`;

ALTER TABLE `profesor`
    ADD CONSTRAINT `profesor_user_id_fk`
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL;

CREATE UNIQUE INDEX `profesor_user_id_unique`
    ON `profesor` (`user_id`);

ALTER TABLE `alumno`
    ADD COLUMN `user_id` INT UNSIGNED NULL AFTER `id_alumno`;

ALTER TABLE `alumno`
    ADD CONSTRAINT `alumno_user_id_fk`
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL;

CREATE UNIQUE INDEX `alumno_user_id_unique`
    ON `alumno` (`user_id`);

-- ----------------------------------------------------------------------
-- Versión "down"
-- ----------------------------------------------------------------------

ALTER TABLE `profesor`
    DROP FOREIGN KEY `profesor_user_id_fk`;

DROP INDEX `profesor_user_id_unique`
    ON `profesor`;

ALTER TABLE `profesor`
    DROP COLUMN `user_id`;

ALTER TABLE `alumno`
    DROP FOREIGN KEY `alumno_user_id_fk`;

DROP INDEX `alumno_user_id_unique`
    ON `alumno`;

ALTER TABLE `alumno`
    DROP COLUMN `user_id`;

