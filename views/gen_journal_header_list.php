<table class='sub-table' width='100%'>
	<thead class="highlight-row-blue">
		<tr>
			<th class="main-body-text-center">Ref. ID No.</th>
			<th class="main-body-text-center">Entry Date</th>
			<th class="main-body-text-center">Period</th>
			<th class="main-body-text-center">Posting Date</th>
			<th class="main-body-text-center">Txn Type</th>
			<th class="main-body-text-center">Txn Class</th>
			<th class="main-body-text-center">Description</th>
			<th class="main-body-text-center">Total Debit Amount</th>
			<th class="main-body-text-center">Total Credit Amount</th>
			<th class="main-body-text-center">Total Debit EWT</th>
			<th class="main-body-text-center">Total Credit EWT</th>
			<th class="main-body-text-center">Total Debit VAT</th>
			<th class="main-body-text-center">Total Credit VAT</th>
			<th class="main-body-text-center">Total Debit NET</th>
			<th class="main-body-text-center">Total Credit NET</th>
		</tr>
	</thead>
	<tbody>
<?php
		if (isset($list_journal)) {
			for ($i=0; $i<$list_rows; $i++) {
?>
		<tr class="highlight-row-yellow">
			<td class="main-body-text-left"><a href="#" onclick="showJournalDebitDetails('<?php echo $list_journal[$i]['journal_ref_id'] ?>');showJournalCreditDetails('<?php echo $list_journal[$i]['journal_ref_id'] ?>')"><?php echo $list_journal[$i]['journal_ref_id'] ?></a></td>
			<td class="main-body-text-center"><?php echo $list_journal[$i]['journal_entry_date'] ?></td>
			<td class="main-body-text-center"><?php echo $list_journal[$i]['journal_period'] ?></td>
			<td class="main-body-text-center"><?php echo $list_journal[$i]['journal_posting_date'] ?></td>
			<td class="main-body-text-center"><?php echo $list_journal[$i]['journal_txn_type'] ?></td>
			<td class="main-body-text-center"><?php echo $list_journal[$i]['journal_txn_class'] ?></td>
			<td class="main-body-text-left"><?php echo $list_journal[$i]['journal_description'] ?></td>
			<td class="main-body-text-right"><?php echo number_format($list_journal[$i]['journal_total_debit_amount'], 2) ?></td>
			<td class="main-body-text-right"><?php echo number_format($list_journal[$i]['journal_total_credit_amount'], 2) ?></td>
			<td class="main-body-text-right"><?php echo number_format($list_journal[$i]['journal_total_debit_wtax'], 2) ?></td>
			<td class="main-body-text-right"><?php echo number_format($list_journal[$i]['journal_total_credit_wtax'], 2) ?></td>
			<td class="main-body-text-right"><?php echo number_format($list_journal[$i]['journal_total_debit_vat'], 2) ?></td>
			<td class="main-body-text-right"><?php echo number_format($list_journal[$i]['journal_total_credit_vat'], 2) ?></td>
			<td class="main-body-text-right"><?php echo number_format($list_journal[$i]['journal_total_debit_net'], 2) ?></td>
			<td class="main-body-text-right"><?php echo number_format($list_journal[$i]['journal_total_credit_net'], 2) ?></td>
		</tr>
<?php 
			}
		} else {
?>
		<tr>
			<td cols='7'><i>No records found</i></td>
		</tr>
<?php 
		}
?>
	</tbody>						
</table>


