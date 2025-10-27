$nwin = 300;    # I think the lottery picked about 250-something winners last year, the rest were golden tickets and such
$nlot = 1000000; # someone could quantify the margin of error based on this, with statistics you would oh look a bird

for ($pid = 0; <>; ) {
  ($nt, $np) = split(/\s+/, s/,//r);
  ($nt > 0 and $np > 0 and not exists $wcnt{$nt}) or die "invalid input: $_\n";
  $wbin{$pid} = $nt;
  $wcnt{$nt} = 0;
  push(@tk, ($pid++) x $nt) while ($np-- > 0);
}

printf("%d tickets for %d entrants, running %d lotteries for %d winners:\n", $tkcnt = @tk, $pid, $nlot, $nwin);

for ($x = 0; $x < $nlot; $x++) {
  %in = { };
  $in{$id = $tk[rand($tkcnt)]}++ == 0 and exists $wbin{$id} and $wcnt{$wbin{$id}}++ while (%in < $nwin);
}

printf("%d tickets: %.2f%% win\n", $_, $wcnt{$_} * 100.0 / $nlot) foreach (sort { $a <=> $b } keys %wcnt);
