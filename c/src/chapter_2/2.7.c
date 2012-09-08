#include <stdio.h>

int main(void)
{
	float plank_length = 10.0f;
	float piece_count = 4.0f;
	float piece_length = 0.0f;
	
	piece_length = plank_length / piece_count;
	printf("A plank %.2f feet long can be cut into %.0f piece %.2f feet long.", plank_length, piece_count, piece_length);
	return 0;	
}
