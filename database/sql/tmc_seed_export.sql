-- Tapan Memorial Club starter SQL export
-- Run after migrations, or use as reference dataset.

INSERT INTO settings (`group`, `key`, `value`, `type`, `is_public`, `created_at`, `updated_at`)
VALUES
('club', 'club_name', 'Tapan Memorial Club', 'text', 1, NOW(), NOW()),
('club', 'club_estd', '1942', 'text', 1, NOW(), NOW());

INSERT INTO performances (`year`, `tournament`, `position`, `matches_played`, `wins`, `losses`, `points`, `highlight_color`, `description`, `is_featured`, `created_at`, `updated_at`)
VALUES
(2020, 'Roxx Champions Cup', 'Champion', 8, 7, 1, 14, '#D4AF37', 'Dominant all-round season.', 1, NOW(), NOW()),
(2024, 'District League Invitational', 'Runner-up', 9, 6, 3, 12, '#4DB7FF', 'High-tempo cricket with disciplined bowling.', 1, NOW(), NOW());
