<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('seller/transaction/order-list') ?'active':'' }}"><a href="{{route('seller.transaction.order-list')}}">{{translate('order_Transactions')}}</a></li>
        <li class="{{ Request::is('seller/transaction/expense-list') ?'active':'' }}"><a href="{{route('seller.transaction.expense-list')}}">{{translate('expense_Transactions')}}</a></li>
    </ul>
</div>
