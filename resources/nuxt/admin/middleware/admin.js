export default function ({$auth, redirect}) {
  if (!($auth.user.role === 'admin')) {
    return redirect('/warehouses/' + $auth.user.warehouse_id)
  }
}
