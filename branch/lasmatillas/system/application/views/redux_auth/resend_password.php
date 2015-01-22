
<?php echo form_open('welcome/forgotten_password_complete'); ?>

<table>
    <thead>
        <tr>
            <th colspan="2">Required Fields</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Verification Code</td>
            <td><?php echo form_input('code', set_value('code')); ?></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"><?php echo form_submit('submit', 'Send New Password'); ?></td>
        </tr>
    </tfoot>
</table>

<?php echo form_close(''); ?>

