SELECT action 
FROM up_permissions p
inner join up_permissions_role_links pr on p.id = pr.permission_id 
inner join (
    SELECT id 
    FROM up_roles 
    where type='public'
) r2 on pr.role_id = r2.id
