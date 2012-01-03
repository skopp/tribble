SELECT 
  tr_tribbles.tribble_text,
  tr_tribbles.tribble_title,
  tr_comments.comment_text,
  tr_replies_ref.ref_timestamp as ts,
  tr_users.user_realname AS com_username,
  tr_users.user_id AS com_userid,
  tr_users1.user_realname AS reb_username,
  tr_users1.user_id AS reb_userid,
  tr_images.image_path AS image,
  tr_tribbles.tribble_id
FROM
  tr_replies_ref
  LEFT OUTER JOIN tr_comments ON (tr_replies_ref.ref_comment_id = tr_comments.comment_id)
  LEFT OUTER JOIN tr_tribbles ON (tr_replies_ref.ref_rebound_id = tr_tribbles.tribble_id)
  LEFT OUTER JOIN tr_users ON (tr_comments.comment_user_id = tr_users.user_id)
  LEFT OUTER JOIN tr_users tr_users1 ON (tr_tribbles.tribble_user_id = tr_users1.user_id)
  LEFT OUTER JOIN tr_images ON (tr_tribbles.tribble_id = tr_images.image_tribble_id)
WHERE
  tr_replies_ref.ref_tribble_id = 54
