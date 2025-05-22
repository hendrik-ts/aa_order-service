INSERT INTO bill_items (
    id,
    code,
    bill_id,
    description,
    price,
    quantity,
    created_at,
    updated_at
  )
VALUES (
    id:INTEGER,
    bill_id:INTEGER,
    'description:varchar',
    price:numeric,
    quantity:INTEGER,
    'created_at:datetime',
    'updated_at:datetime'
  );