$nwin = 250;    # I think the lottery picked about 250-something winners last year, the rest were golden tickets and such
$nlot = 200000; # someone could quantify the margin of error based on this, with statistics you would oh look a bird

for ($pid = 0; <>; ) {
  ($nt, $np) = split(/\s+/, s/,//r);
  ($nt > 0 and $np > 0 and not exists $nw{$nt}) or die "invalid input: $_\n";
  ($ne{$nt}, $nw{$nt}) = ($np, 0);
  push(@tk, ($pid++, $nt) x $nt) while ($np-- > 0);
}

printf("%d tickets for %d entrants, running %d lotteries for %d winners:\n", $tkcnt = int(@tk/2), $pid, $nlot, $nwin);

for ($x = 0; $x < $nlot; $x++) {
  %in = { };
  $in{$id = $tk[$idx = int(rand($tkcnt))*2]}++ == 0 and ++$nw{$tk[$idx+1]} while (%in < $nwin);
}
printf("%d tickets: %.2f%% (%.1f/%d)\n", $_, ($nw{$_} * 100.0 / $nlot) / $ne{$_}, $nw{$_} / $nlot, $ne{$_}) foreach (sort { $a <=> $b } keys %nw);
